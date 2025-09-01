
document.addEventListener('DOMContentLoaded', function () {
    if (typeof PhotoSwipeLightbox === 'undefined' || typeof PhotoSwipe === 'undefined') {
        console.warn('PhotoSwipe is not loaded!');
        return;
    }
    const lightbox = new PhotoSwipeLightbox({
        gallery: '#SlickPhotoswipGallery',
        children: 'a',
        pswpModule: PhotoSwipe
    });
    lightbox.init();
});
