<?php
/**
 * @package     BR Simple Videos
 * @author      Janderson Moreira
 * @copyright   Copyright (C) 2026 Janderson Moreira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$videoId  = $displayData['videoId'];
$width    = $displayData['width'];
$ratio    = $displayData['ratio'];
$align    = $displayData['align'];
$cinema   = $displayData['cinema'];
$noCookie = $displayData['noCookie'] ? '-nocookie' : '';
$autoplay = $displayData['autoplay'] ? '&autoplay=1&mute=1' : '';
$lazy     = $displayData['lazy'] ? 'loading="lazy"' : '';

$embedUrl = "https://www.youtube{$noCookie}.com/embed/{$videoId}?rel=0{$autoplay}";

$margin = '0 auto';
if ($align === 'left') $margin = '0 auto 0 0';
if ($align === 'right') $margin = '0 0 0 auto';
?>

<style>
    /* Estilo do botão que abre o modal */
    .br-cinema-trigger {
        width: 100%;
        border: none;
        padding: 0;
        background: none;
        cursor: pointer;
        position: relative; /* Garante que o ícone de play fique sobre a imagem */
        display: block;
    }
    .br-play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 0, 0, 0.9);
        color: #fff;
        width: 68px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        z-index: 2; /* Garante que fique em cima */
        pointer-events: none;
    }
    
    /* Força a centralização do modal */
    .br-dialog-modal {
        border: none;
        border-radius: 12px;
        padding: 0;
        width: 90vw;
        max-width: 900px;
        background: #000;
        overflow: visible;
        position: fixed; /* Força posicionamento em relação à tela */
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        margin: 0;
    }

    .br-dialog-modal::backdrop {
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(4px);
    }

    .br-close-container {
        position: absolute;
        top: -50px;
        right: 0;
        z-index: 9999;
    }

    .br-close-btn {
        background: #fff;
        border: none;
        color: #000;
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-family: sans-serif;
        display: block;
    }
</style>

<div class="br-video-wrapper" style="max-width: <?php echo $width; ?>; margin: <?php echo $margin; ?>;">
    <?php if ($cinema) : ?>
        <button class="br-cinema-trigger" onclick="document.getElementById('br-modal-<?php echo $videoId; ?>').showModal()">
            <img src="https://img.youtube.com/vi/<?php echo $videoId; ?>/maxresdefault.jpg" style="width:100%; border-radius:8px; display:block;" alt="Play">
            <div class="br-play-icon">▶</div>
        </button>

        <dialog id="br-modal-<?php echo $videoId; ?>" class="br-dialog-modal" onclick="if(event.target==this) this.close()">
            <div style="position:relative; width:100%; aspect-ratio:16/9;">
                <div class="br-close-container">
                    <button class="br-close-btn" onclick="this.closest('dialog').close()">Close [X]</button>
                </div>
                <iframe src="<?php echo $embedUrl; ?>" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        </dialog>
    <?php else : ?>
        <div style="position: relative; width: 100%; aspect-ratio: <?php echo $ratio; ?>;">
            <iframe src="<?php echo $embedUrl; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allowfullscreen <?php echo $lazy; ?>></iframe>
        </div>
    <?php endif; ?>
</div>