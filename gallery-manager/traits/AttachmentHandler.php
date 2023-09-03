<?php

trait AttachmentHandler
{
    public function attach_images($uploaded_images, $gallery_dir, $gallery_url)
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
}