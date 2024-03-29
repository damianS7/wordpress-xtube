<?php
namespace Xtube\Backend\Controllers;

use Xtube\Backend\XtubeBackend;
use Xtube\Backend\Importers\XVideos;
use Xtube\Backend\Importers\Pornhub;
use Xtube\Backend\Importers\Youtube;
use Xtube\Backend\Models\Video;
use Xtube\Backend\Models\Tag;

class VideoImportController {
    public static function import($video) {
        $video_id = Video::add_video_ignore(
            $video->url,
            $video->title,
            $video->img_src,
            $video->duration,
            $video->upvotes,
            $video->downvotes,
            $video->views,
            $video->iframe
        );

        // Si no encontramos el id es aue no se inserto
        if ($video_id === null || $video_id == 0) {
            return false;
        }
        
        // Agregar tags del video a la db
        $tags = explode(',', sanitize_text_field($_POST['tags']));
                    
        foreach ($tags as $tag) {
            $tag = trim($tag);

            // Tag sin contenido no permitidos
            if (empty($tag)) {
                continue;
            }

            // Insertamos el tag en la db
            Tag::add_tag_ignore($tag);
                        
            // Buscamos el id del tag
            $tag_id = Tag::get_tag_id($tag);

            Tag::add_tag_to_video($video_id, $tag_id);
        }
        return true;
    }
    
    public function search($server, $keyword, $page = '1') {
        switch ($server) {
            case 'xvideos': return XVideos::search($keyword, $page);
            case 'pornhub': return Pornhub::search($keyword, $page);
            case 'youtube': return Youtube::search($keyword, $page);
            default: return XVideos::search($keyword, $page);
        }
    }

    // Metodo para procesar los formularios (POST)
    public function handle_forms() {
        if (isset($_POST['search_submit'])) {
            $keyword = sanitize_text_field($_POST['search']);
            $server = sanitize_text_field($_POST['server']);

            $url = add_query_arg(
                array(
                    'page' => 'xtube-import',
                    'xtb_server' => $server,
                    'xtb_keyword' => $keyword,
                    'xtb_pagination' => 1),
                admin_url() . 'admin.php'
            );

            if (wp_redirect($url)) {
                exit;
            }
        }

        if (isset($_POST['import_submit'])) {
            $video_search = get_transient('xtb_last_videos_search');
            $videos_index = $_POST['video_list'];
            $videos_marked_to_import = array();
            $imported = 0;
            // Indices seleccionados
            foreach ($videos_index as $index) {
                $videos_marked_to_import[] = $video_search['videos'][$index];
                $video = $video_search['videos'][$index];
                
                if (self::import($video)) {
                    $imported++;
                }
            }

            $data['success'] = 'Imported: ' . $imported . ' / ' . count($videos_marked_to_import);
            //$data['videos_to_import'] = $videos_marked_to_import;
            
            set_transient('imports_view_data', $data, 60*60*2);

            if (wp_redirect($_SERVER['HTTP_REFERER'])) {
                exit;
            }
        }
    }

    // Metodo para renderizar la vista.
    public function render() {
        $server = XtubeBackend::get_query_var('xtb_server');
        $keyword = XtubeBackend::get_query_var('xtb_keyword');
        $page = XtubeBackend::get_query_var('xtb_pagination');

        if (empty($server) || empty($keyword) || empty($page)) {
            return XtubeBackend::view('imports.php', null);
        }
        
        $search_videos = $this->search($server, $keyword, $page);
        if (count($search_videos) > 0) {
            set_transient('xtb_last_videos_search', $search_videos, 60*60*2);
        }
        
        $view_data = get_transient('imports_view_data');
        if (!empty($view_data)) {
            delete_transient('imports_view_data');
            $data = array_merge($view_data, $search_videos);
        } else {
            $data = $search_videos;
        }
        return XtubeBackend::view('imports.php', $data);
    }
}