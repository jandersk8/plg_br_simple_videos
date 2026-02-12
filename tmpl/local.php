<?php
/**
 * @package     BR Simple Videos
 * @author      Janderson Moreira
 * @copyright   Copyright (C) 2026 Janderson Moreira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$link     = $displayData['link'];
$width    = $displayData['width'];
$ratio    = $displayData['ratio'];
$align    = $displayData['align'];
$cinema   = $displayData['cinema'];
$autoplay = $displayData['autoplay'] ? 'autoplay muted' : '';

$uniqueId = md5($link);
$margin = ($align === 'left') ? '0 auto 0 0' : (($align === 'right') ? '0 0 0 auto' : '0 auto');
?>

<style>
    .br-local-trigger {
        width: 100%; border: none; padding: 0; background: #000; cursor: pointer; position: relative; display: block; overflow: hidden; border-radius: 8px;
    }
    .br-local-play-icon {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.7); color: #fff; width: 68px; height: 48px;
        border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; z-index: 10; border: 2px solid #fff;
    }
    .br-local-modal {
        border: none; border-radius: 12px; padding: 0; width: 90vw; max-width: 900px; background: #000;
        overflow: visible; position: fixed; top: 50% !important; left: 50% !important;
        transform: translate(-50%, -50%) !important; margin: 0;
    }
    .br-local-modal::backdrop { background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(4px); }
    .br-l-close-btn { position: absolute; top: -50px; right: 0; background: #fff; border: none; color: #000; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    
    /* O canvas fica escondido, só gera a imagem */
    .br-thumb-canvas { display: none; }
    .br-preview-img { width: 100%; aspect-ratio: <?php echo $ratio; ?>; object-fit: cover; display: block; background: #000; }
</style>

<div class="br-video-wrapper" style="max-width: <?php echo $width; ?>; margin: <?php echo $margin; ?>;">
    <?php if ($cinema) : ?>
        <button class="br-local-trigger" onclick="document.getElementById('br-modal-l-<?php echo $uniqueId; ?>').showModal()">
            <img id="thumb-<?php echo $uniqueId; ?>" class="br-preview-img" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="Loading...">
            <div class="br-local-play-icon">▶</div>
        </button>

        <dialog id="br-modal-l-<?php echo $uniqueId; ?>" class="br-local-modal" onclick="if(event.target==this) this.close()">
            <div style="position:relative; width:100%; aspect-ratio:<?php echo $ratio; ?>;">
                <button class="br-l-close-btn" onclick="this.closest('dialog').close()">Close [X]</button>
                <video id="vid-<?php echo $uniqueId; ?>" controls style="width:100%; height:100%; background:#000;">
                    <source src="<?php echo $link; ?>" type="video/mp4">
                </video>
            </div>
        </dialog>
    <?php else : ?>
        <video controls <?php echo $autoplay; ?> preload="metadata" playsinline style="width: 100%; aspect-ratio: <?php echo $ratio; ?>; background: #000; border-radius: 4px;">
            <source src="<?php echo $link; ?>#t=0.5" type="video/mp4">
        </video>
    <?php endif; ?>
</div>

<video id="temp-vid-<?php echo $uniqueId; ?>" style="display:none;" muted preserveAspectRatio="xMidYMid slice">
    <source src="<?php echo $link; ?>" type="video/mp4">
</video>
<canvas id="canvas-<?php echo $uniqueId; ?>" class="br-thumb-canvas"></canvas>

<script>
(function() {
    const video = document.getElementById('temp-vid-<?php echo $uniqueId; ?>');
    const canvas = document.getElementById('canvas-<?php echo $uniqueId; ?>');
    const img = document.getElementById('thumb-<?php echo $uniqueId; ?>');

    if (!video || !img) return;

    video.addEventListener('loadeddata', function() {
        // Pula para o segundo 1 para evitar tela preta inicial
        video.currentTime = 1;
    });

    video.addEventListener('seeked', function() {
        // Tira o "print" do vídeo e joga no canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Converte o canvas para imagem e coloca no <img>
        img.src = canvas.toDataURL('image/jpeg');
        // Limpa o vídeo temporário da memória
        video.remove();
        canvas.remove();
    });
})();
</script>