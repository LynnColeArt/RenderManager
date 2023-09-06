<?php
trait FormHandler {
    public function handle_form_submission(): void {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["gallery_zip"]["tmp_name"])) {
            $gallery_dir = wp_upload_dir()["path"];
            $gallery_url = wp_upload_dir()["url"];
            $zip_file = $_FILES["gallery_zip"]["tmp_name"];
            $zip = new ZipArchive();

            if ($zip->open($zip_file) === true) {
                $extract_dir = $gallery_dir . "/".uniqid().'/'; //This creates a unique directory for each zip uploaded.
                $zip->extractTo($extract_dir);
                $zip->close();
                $uploaded_images = [];
                $png_data = [];

                foreach (new DirectoryIterator($extract_dir) as $fileInfo) {
                    if ($fileInfo->isFile() && $fileInfo->getExtension() === "png") {
                        $file_path = $fileInfo->getPathname();
                        $png = new PNGReader($file_path);
                        $raw_text_data = $png->getChunks('tEXt');
                       


                        if (isset($raw_text_data[0])) {

                            //Cleaning up our raw image data a little bfore passing it on to the parser.
                            $line =  str_ireplace(['parameters', 'negative prompt:', 'steps:'], [', prompt: ', ', negative prompt: ', ', steps: '], $raw_text_data[0] . '%');
                            $png_data[] = $this->parse_raw_data($line);
                        }

                        $uploaded_images[] = [
                            "file_path" => $file_path,
                            "attachment_id" => 0,
                        ]; 
                    }
                }

                $attached_images = $this->attach_images($uploaded_images, $gallery_dir, $gallery_url);

                if (!empty($attached_images)) {
                    $this->create_ai_art_post($attached_images, $png_data);
                    wp_redirect(admin_url('edit.php?post_type=ai_art_gallery'));
                    return;
                }
            }
        }

        wp_die("Failed to upload Zip file.");
    }

}