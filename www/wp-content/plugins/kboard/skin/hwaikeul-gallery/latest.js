/**
 * @author https://www.cosmosfarm.com
 */

jQuery(document).ready(function(){
	jQuery('.kboard-light-gallery', '#kboard-hwaikeul-gallery-latest').lightGallery({
		select: '.target-image',
		download: false,
		getCaptionFromTitleOrAlt: false
	});
	
	jQuery('.kboard-hwaikeul-gallery-thumbnail', '#kboard-hwaikeul-gallery-latest').click(function(){
		if(jQuery('.target-image', this).length > 0){
			jQuery('.target-image', this).get(0).click();
		}
	});
});