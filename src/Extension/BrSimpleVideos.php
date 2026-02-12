<?php
/**
 * @package     BR Simple Videos
 * @author      Janderson Moreira
 * @copyright   Copyright (C) 2026 Janderson Moreira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace BrSimple\Plugin\Content\Videos\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Layout\FileLayout;

/**
 * BR Simple Videos Content Plugin
 */
class BrSimpleVideos extends CMSPlugin implements SubscriberInterface
{
    /**
     * Registra os eventos que o plugin vai escutar
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare' => 'onContentPrepare',
        ];
    }

    /**
     * Evento acionado quando o conteúdo está sendo preparado para exibição
     */
    public function onContentPrepare(Event $event)
    {
        $row = $event->getArgument(1);

        // Verifica se a tag existe no texto
        if (empty($row->text) || strpos($row->text, '{brvideos') === false) {
            return;
        }

        /**
         * Regex explicada:
         * {brvideos        -> Abre a tag
         * \s*([^}]*)       -> Grupo 1: Captura atributos (width, autoplay, etc) antes do fechamento "}"
         * }                -> Fecha a tag de abertura
         * \s*(.*?)\s* -> Grupo 2: Captura o Link ou ID (conteúdo da tag)
         * {\/brvideos}     -> Tag de fechamento
         */
        $regex = '/{brvideos\s*([^}]*)}\s*(.*?)\s*{\/brvideos}/i';

        $row->text = preg_replace_callback($regex, function($matches) {
            // $matches[1] são os atributos, $matches[2] é o link/ID
            return $this->renderVideo($matches[1], $matches[2]);
        }, $row->text);
    }

    /**
     * Processa os atributos e renderiza o player correto
     */
    protected function renderVideo($attributes, $link)
    {
        $link = trim(strip_tags($link));
        
        // 1. Captura Atributos dinamicamente (aceita aspas duplas, simples ou sem aspas para números)
        $attribs = [];
        if (!empty($attributes)) {
            // Regex para pegar chave="valor" ou chave='valor' ou chave=valor
            preg_match_all('/(\w+)\s*=\s*["\']?([^"\'\s>]+)["\']?/', $attributes, $attrMatches);
            if (!empty($attrMatches[1])) {
                foreach ($attrMatches[1] as $key => $name) {
                    $attribs[strtolower($name)] = $attrMatches[2][$key];
                }
            }
        }

        // 2. Lógica de Prioridade: Tag > Configuração do Plugin
        $width = $attribs['width'] ?? $this->params->get('max_width', '100%');
        
        // Adiciona "px" automaticamente se o usuário digitar apenas o número na tag
        if (is_numeric($width)) {
            $width .= 'px';
        }

        $autoplay = isset($attribs['autoplay']) ? (int)$attribs['autoplay'] : (int)$this->params->get('autoplay', '0');
        
        $ratio    = $this->params->get('aspect_ratio', '16/9');
        $align    = $this->params->get('alignment', 'center');
        $lazy     = $this->params->get('lazy_load', '1');
        $noCookie = $this->params->get('youtube_nocookie', '1');
        $cinema   = $this->params->get('cinema_mode', '0');

        // 3. Identificar Provedor e ID
        $type = 'local';
        $videoId = $link;

        if (preg_match('/(youtube\.com|youtu\.be)\/(watch\?v=|v\/|u\/|embed\/|shorts\/)?([^#\&\?]*).*/', $link, $idMatches)) {
            $type = 'youtube';
            $videoId = $idMatches[3];
        } elseif (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/', $link, $idMatches)) {
            $type = 'vimeo';
            $videoId = $idMatches[3];
        } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $link)) {
            $type = 'local';
        } else {
            // Fallback para IDs puros
            if (strlen($link) === 11) { $type = 'youtube'; }
            elseif (is_numeric($link)) { $type = 'vimeo'; }
        }

        // 4. Montar os dados para o layout
        $layoutData = [
            'link'     => $link,
            'videoId'  => $videoId,
            'width'    => $width,
            'autoplay' => $autoplay,
            'ratio'    => $ratio,
            'align'    => $align,
            'lazy'     => $lazy,
            'noCookie' => $noCookie,
            'cinema'   => $cinema
        ];

        $layout = new FileLayout($type, JPATH_PLUGINS . '/content/br_simple_videos/tmpl');
        
        return $layout->render($layoutData);
    }
}