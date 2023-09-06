<?php

trait AttachmentHandler
{
    public function attach_images(array $uploaded_images, string $gallery_dir, string $gallery_url)
    {
        $attachments = [];
    
        foreach ($uploaded_images as $image) {
            $file_path = $image["file_path"];
            $attachment_title = uniqid('attachment_', true);
            $file_type = wp_check_filetype($file_path, null);
    
            $attachment_data = array(
                'guid'           => $gallery_url . '/' . $attachment_title,
                'post_mime_type' => $file_type['type'],
                'post_title'     => $attachment_title,
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
    
            $attachment_id = wp_insert_attachment($attachment_data, $file_path);
            if ($attachment_id) {
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
                wp_update_attachment_metadata($attachment_id, $attachment_data);
                $attachments[] = $attachment_id;
            }
        }
    
        return $attachments;
    }

    public function create_ai_art_post(array $attached_images, array $png_data): void {
        foreach ($attached_images as $index => $attached_image) {
    
            $attachment_id = $attached_image;
            $post_title = 'Item: ' . uniqid();
            
            $post_data = array(
                'post_title' => $post_title,
                'post_status' => 'publish',
                'post_type' => 'ai_art_gallery',
                'post_content' => '',
            );
    
            $post_id = wp_insert_post($post_data);
    
            if ($post_id) {
                set_post_thumbnail($post_id, $attachment_id);
    
                $meta_data = array(
                    'prompt' => @$png_data[$index]['top']['prompt'],
                    'negative_prompt' => @$png_data[$index]['top']['negative_prompt'],
                    'meta_json' => @$png_data[$index]['json'],
                );
    
                foreach ($meta_data as $meta_key => $meta_value) {
                    update_post_meta($post_id, $meta_key, $meta_value);
                }
            }
        }
    }
}