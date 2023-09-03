<?php

trait AiArtPresentation
{
    public array $meta_fields = [
        [
            'id' => 'prompt',
            'label' => 'Prompt',
        ],
        [
            'id' => 'negative_prompt',
            'label' => 'Negative Prompt',
        ],
        [
            'id' => 'meta_json',
            'label' => 'Meta Json',
        ],
    ];

    public function add_ai_art_gallery_meta_boxes(): void
    {
        foreach ($this->meta_fields as $field) {
            add_meta_box(
                'ai_art_gallery_' . $field['id'],
                $field['label'],
                [$this, 'render_ai_art_gallery_meta_box'],
                'ai_art_gallery',
                'normal',
                'default',
                $field
            );
        }
    }

    public function render_ai_art_gallery_meta_box($post, $metabox): void
    {
        $field = $metabox['args'];
        $value = get_post_meta($post->ID, $field['id'], true);
        ?>
        <textarea style="width:100%;height:5em" name="<?php echo esc_attr($field['id']); ?>"><?php echo esc_attr($value); ?></textarea>
        <?php
    }

    public function save_ai_art_gallery_meta($post_id): void
    {
        foreach ($this->meta_fields as $field) {
            if (isset($_POST[$field['id']])) {
                update_post_meta($post_id, $field['id'], sanitize_text_field($_POST[$field['id']]));
            }
        }
    }

    public function add_custom_columns($columns): array
    {
        $new_columns = [
            'cb' => '<input type="checkbox" />',
            'title' => 'Title',
            'prompt' => 'Prompt',
            'negative_prompt' => 'Negative Prompt',
            'featured_image' => 'Featured Image',
            'date' => 'Date',
        ];

        return $new_columns;
    }

    public function render_custom_columns($column, $post_id): void
    {
        switch ($column) {
            case 'prompt':
                $positive_prompt = get_post_meta($post_id, 'prompt', true);
                echo esc_html($positive_prompt);
                break;

            case 'negative_prompt':
                $negative_prompt = get_post_meta($post_id, 'negative_prompt', true);
                echo esc_html($negative_prompt);
                break;

            default:
                break;
        }
    }

    public function populate_custom_columns($column, $post_id): void
    {
        if ($column === 'featured_image') {
            if (has_post_thumbnail($post_id)) {
                echo the_post_thumbnail('thumbnail');
            } else {
                echo 'No Image';
            }
        }
    }

    public function customize_columns(): void
    {
        // Add the featured image column to your custom post type
        add_filter('manage_ai_art_gallery_posts_columns', [$this, 'add_featured_image_column']);
        add_action('manage_ai_art_gallery_posts_custom_column', [$this, 'display_featured_image_column'], 10, 2);
       
    }

    public function add_featured_image_column($columns): array
    {
        // Add the featured image column after the title column
        $columns['featured_image'] = 'Featured Image';
        return $columns;
    }

    public function display_featured_image_column($column, $post_id): void
    {
        if ($column === 'featured_image') {
            // Get the featured image URL
            $featured_image_url = get_the_post_thumbnail_url($post_id, 'thumbnail');

            if ($featured_image_url) {
                // Display the featured image
                echo '<img src="' . esc_attr($featured_image_url) . '" alt="Featured Image" style="max-width: 250px; height: auto;" />';
            } else {
                echo '-';
            }
        }
    }
}