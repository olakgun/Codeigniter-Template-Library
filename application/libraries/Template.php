<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Template Library for Codeigniter Web Framework
 *
 * @version v1.0
 * @author Turan Karatuğ <tkaratug@hotmail.com.tr>
 * @version v2.0
 * @author Olcay Akgun <info@ankadata.com>
 * @access public
 */
class Template
{
    // Codeigniter Instance
    protected $ci;

    // Platform Folder Name
    private $platform;

    // Theme Folder Name
    private $theme;

    // Head Items
    private $head;

    // Footer Items
    private $footer;

    // data for head
    private $head_data;

    // data for footer
    private $footer_data;

    // View File
    private $layout;

    // Default Theme
    private $default_theme = 'default';

    function __construct()
    {
        $this->ci =& get_instance();

        // load url helper for base_url
        if(!function_exists('base_url'))
            $this->ci->load->helper('url');
    }

    public function css($css_file, $source = 'local')
    {
        // Check is set a platform
        if (!$this->get_platform())
            show_error('Please set platform.<br><br>Example: <br>$this->set_platform(\'public\');');

        if ($source == 'remote') {
            $this->head['css'][] =  $css_file;
        } else {
            $url = 'assets/' . $this->get_platform() . '/' . $this->get_theme() . '/css/' . $css_file;

            // Check is file exists
            if (!file_exists($url))
                show_error("Cannot locate stylesheet file: {$url}.");

            $this->head['css'][] = '<link rel="stylesheet" type="text/css" href="' . base_url($url) . '">';
        }
    }

    public function js($js_file, $location = 'footer', $source = 'local')
    {
        // Check is set a platform
        if (!$this->get_platform())
            show_error('Please set platform.<br><br>Example: <br>$this->set_platform(\'public\');');

        if ($source == 'remote') {
            if($location == 'footer')
                $this->footer['js'][] = $js_file;
            else
                $this->head['js'][] = $js_file;
        } else {
            // if js file is local
            $url = 'assets/' . $this->get_platform() . '/' . $this->get_theme() . '/js/' . $js_file;

            // file exists
            if (!file_exists($url))
                show_error("Cannot locate javascript file: {$url}.");

            if($location=='footer')
                $this->footer['js'][] = '<script type="text/javascript" src="' . base_url($url) . '"></script>';
            else
                $this->head['js'][] = '<script type="text/javascript" src="' . base_url($url) . '"></script>';
        }
    }

    public function meta($meta_name, $meta_content)
    {
        $this->head['meta'][] = '<meta name="' . $meta_name . '" content="' . $meta_content . '">';
    }

    public function title($title)
    {
        $this->head['title'] = '<title>' . $title . '</title>';
    }

    private function get_title()
    {
        return $this->head['title'];
    }

    private function get_css()
    {
        $css = NULL;

        if(isset($this->head['css']))
        {
            foreach ($this->head['css'] as $css_item)
            {
                $css .= $css_item.PHP_EOL;
            }
        }
        return $css;
    }

    private function get_js($location = 'head')
    {
        $js = NULL;

        // js location
        if($location == 'head')
        {
            if(isset($this->head['js']))
            {
                foreach ($this->head['js'] as $js_item)
                {
                    $js .= $js_item.PHP_EOL;
                }
            }
        }
        else
        {
            if(isset($this->footer['js']))
            {
                foreach ($this->footer['js'] as $js_item)
                {
                    $js .= $js_item.PHP_EOL;
                }
            }
        }

        return $js;
    }

    private function get_meta()
    {
        $meta = NULL;

        if(isset($this->head['meta']))
        {
            foreach ($this->head['meta'] as $meta_item)
            {
                $meta .= $meta_item.PHP_EOL;
            }
        }

        return $meta;
    }

    public function canonical($canonical)
    {
        $this->head['canonical'] = $canonical;
    }

    private function get_canonical()
    {
        if(isset($this->head['canonical']))
            return '<link rel="canonical" href="'.base_url($this->canonical).'" />';
        else
            return NULL;
    }

    public function head_data($data)
    {
        $this->head_data = $data;
    }

    private function get_head_data()
    {
        return $this->head_data;
    }

    public function footer_data($data)
    {
        $this->footer_data = $data;
    }

    private function get_footer_data()
    {
        return $this->footer_data;
    }

    public function layout($layout)
    {
        $this->layout = $layout;
    }

    private function get_layout()
    {
        return $this->layout;
    }

    public function theme($theme)
    {
        if (is_dir('assets/' . $this->get_platform() . '/' . $theme))
            $this->theme = $theme;
        else
            show_error("Cannot find theme folder: {$theme}.");
    }

    private function get_theme()
    {
        return $this->theme ? $this->theme : $this->default_theme;
    }

    public function platform($platform)
    {
        if (is_dir('assets/' . $platform))
            $this->platform = $platform;
        else
            show_error("Cannot find platform folder : {$platform}.");
    }

    private function get_platform()
    {
        return $this->platform;
    }

    public function render($data = array() , $head_footer_is_visible = true)
    {
        if($head_footer_is_visible == true) {
            $data_head = [
                'canonical' => $this->get_canonical(),
                'title' => $this->get_title(),
                'meta' => $this->get_meta(),
                'css' => $this->get_css(),
                'js' => $this->get_js('head'),
                'head_data' => $this->get_head_data()
            ];

            $this->ci->load->view( $this->get_platform() . '/' . $this->get_theme() . '/layouts/header_view', $data_head);
        }

        $this->ci->load->view( $this->get_platform() . '/' . $this->get_theme() . '/' . $this->get_layout(),$data);

        if($head_footer_is_visible == true) {
            $data_footer = [
                'js' => $this->get_js('footer'),
                'footer_data' => $this->get_footer_data()
            ];
            $this->ci->load->view( $this->get_platform() . '/' . $this->get_theme() . '/layouts/footer_view', $data_footer);
        }
    }
}