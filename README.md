# BR Simple Videos for Joomla 5 & 6

A lightweight, high-performance content plugin to embed videos from YouTube, Vimeo, or local MP4 files using simple shortcodes. Optimized for Joomla 6, it features native browser capabilities for a seamless user experience without heavy dependencies.

## ✨ Key Features

* **Universal Support**: YouTube (URLs, Shorts, IDs), Vimeo (URLs, IDs), and Local MP4 files.
* **Smart Thumbnails**: Automatically generates preview images for local MP4 files using HTML5 Canvas (no server-side processing needed).
* **Cinema Mode (Lightbox)**: Features a modern, centered lightbox using the native HTML5 `<dialog>` element.
* **Click-to-Close**: Intuitive UX that allows closing the lightbox by clicking outside the video or on the dedicated close button.
* **Zero-Jank Responsiveness**: Uses modern CSS `aspect-ratio` to maintain perfect proportions (16:9, 4:3, 1:1, or 9:16) on any device.
* **Performance Focused**: 
    * Native Lazy Loading support.
    * YouTube "No-Cookie" privacy mode.
    * Automated "Mute" for Autoplay compliance in modern browsers.
* **Tag Overrides**: Customize individual videos directly in your articles: 
    `{brvideos width="600" autoplay="1"}...{/brvideos}`

## 📖 Usage

Simply wrap your video link or ID with the `{brvideos}` tag:

**YouTube:**
`{brvideos}https://www.youtube.com/watch?v=i833p2cH4uY{/brvideos}` or `{brvideos}i833p2cH4uY{/brvideos}`

**Vimeo:**
`{brvideos}https://vimeo.com/76979871{/brvideos}` or `{brvideos}76979871{/brvideos}`

**Local Video:**
`{brvideos}images/videos/my-clip.mp4{/brvideos}`

**With Overrides:**
`{brvideos width="450" autoplay="1"}URL_OR_ID{/brvideos}`

## 🛠️ Configuration

Configure global defaults in the Joomla Plugin Manager:
* **Default Max Width**: Set a standard width (e.g., 100% or 800px).
* **Alignment**: Choose Left, Center, or Right.
* **Aspect Ratio**: Define the default proportion for your players.
* **Cinema Mode**: Enable or disable the Lightbox globally.
* **Privacy**: Toggle the `youtube-nocookie.com` domain.

## 📝 License
GPL-2.0 or later.
