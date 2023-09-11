<?php
/*
Template Name: Custom Gallery Template
*/

get_header();

// Get the current post
global $post;

if (have_posts()) :
    while (have_posts()) : the_post();
        // Display the post title if needed
        echo '<h1>' . get_the_title() . '</h1>';

        // Get the post content (you can customize this part)
        the_content();

        // Get attached images and display them as a gallery
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post_status' => 'inherit',
            'post_parent' => $post->ID,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ));

        if ($attachments) :
            echo '<div class="gallery-slider-container">';
            echo '<div class="custom-gallery">';
            foreach ($attachments as $attachment) :
                $image_url = wp_get_attachment_image_url($attachment->ID, 'large'); // Change 'large' to the desired image size
                $image_caption = esc_html($attachment->post_excerpt);

                echo '<div class="gallery-slide">';
                echo '<a href="' . $image_url . '" title="' . $image_caption . '" rel="gallery">';
                echo '<img src="' . $image_url . '" alt="' . $image_caption . '">';
                echo '</a>';
                echo '</div>';
            endforeach;
            echo '</div>';
            echo '<button type="button" class="slick-prev" aria-label="Previous" role="button">Previous</button>';
            echo '<button type="button" class="slick-next" aria-label="Next" role="button">Next</button>';
            echo '</div>';
        endif;
    endwhile;
endif;

get_footer();
