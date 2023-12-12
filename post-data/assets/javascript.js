jQuery(document).ready(function ($) {
    $(".postpost").each(function () {
        var post_id = $(this).data('post_id');
        var post_positions = JSON.parse(decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent('post_positions').replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || "{}");

        if (post_positions.hasOwnProperty(post_id)) {
            $(this).css('left', post_positions[post_id]);
        };

        $(this).draggable({
            axis: "x",
            stop: function (event, ui) {

                post_id = $(this).data('post_id');

                position = ui.helper.position().left; // Access position property from ui.helper object

                post_positions[post_id] = position;

                document.cookie = "post_positions=" + encodeURIComponent(JSON.stringify(post_positions)) + "; path=/";

                // Update the category based on position

                var category;

                if (position > 10) {

                    category = 'hell123';


                    alert(category);
                } else if (position >= 10 && position < 300) {

                    category = 'java1234';
                    alert(category);

                } else {

                    alert(category);

                    category = 'Uncategorized';
                    alert(category);
                }

                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'change_post_category_based_on_position',
                        post_id: post_id,
                        position: position
                    },
                
                    success: function (response) {
                        if (response.success) {
                            alert('Post category updated successfully.');
                
                            // Update the category text
                            $(this).find('.category').text(category);
                        } else {
                            // alert('Error: ' + response.data);
                        }
                    },
                    error: function (error) {
                        console.log('Error: ' + error);
                    }
                 
               
 
                });
            }
        });
    });
});


