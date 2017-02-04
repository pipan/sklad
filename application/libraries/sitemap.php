<?php
class Sitemap{
	
	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->model('language_model');
		$this->CI->load->model('user_model');
		$this->CI->load->model('translation_group_model');
		$this->CI->load->model('translation_model');
		$this->CI->load->model('blog_model');
		$this->CI->load->model('project_model');
		$this->CI->load->model('static_page_model');
	}
	
	public function create_map(){
		$project = $this->CI->project_model->get();
		$article = $this->CI->blog_model->get();
		$language = $this->CI->language_model->get();
		$static = $this->CI->static_page_model->get();
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
		$sitemap .= $this->map_static($static, $language);
		$sitemap .= $this->map_project($project, $language);
		$sitemap .= $this->map_article($article, $language);
		$sitemap .= "</urlset>";
		write_file("./Sitemap.xml", $sitemap);
	}
	
	public function map_project($project, $language){
		$map = "";
		foreach ($project as $p){
			foreach ($language as $l){
				//view
				$data = array(
						'loc' => base_url().$l['lang_shortcut']."/project/view/".get_lang_slug($p['project_name'], $l['id'])."-".$p['project_name'],
						'changefreq' => "weekly",
						'priority' => 0.3,
				);
				$map .= $this->build_url($data);
				//gallery
				$data = array(
						'loc' => base_url().$l['lang_shortcut']."/project/gallery/".get_lang_slug($p['project_name'], $l['id'])."-".$p['project_name'],
						'changefreq' => "weekly",
						'priority' => 0.1,
				);
				$map .= $this->build_url($data);
			}
		}
		return $map;
	}
	
	public function map_article($article, $language){
		$map = "";
		foreach ($article as $a){
			foreach ($language as $l){
				$data = array(
						'loc' => base_url().$l['lang_shortcut']."/article/view/".get_lang_slug($a['blog_name'], $l['id'])."-".$a['blog_name'],
						'changefreq' => "weekly",
						'priority' => 0.3,
				);
				$map .= $this->build_url($data);
			}
		}
		return $map;
	}
	
	public function map_static($static, $language){
		$map = "";
		foreach ($static as $s){
			foreach ($language as $l){
				$data = array(
						'loc' => base_url().$l['lang_shortcut']."/".get_lang_slug($s['page_title'], $l['id'])."-".$s['page_title'],
						'changefreq' => "monthly",
						'priority' => 0.5,
				);
				$map .= $this->build_url($data);
			}
		}
		return $map;
	}
	
	public function add_map($add, $language){
		$map = read_file("./Sitemap.xml");
		$map = explode("\n", $map);
		unset($map[sizeof($map) - 1]);
		$map = implode("\n", $map);
		foreach ($add as $a){
			foreach ($language as $l){
				switch ($add['type']){
					case 'project':
						$loc[0] = base_url().$l['lang_shortcut']."/project/view/".get_lang_slug($p['project_name'], $l['id'])."-".$p['project_name'];
						$loc[1] = base_url().$l['lang_shortcut']."/project/gallery/".get_lang_slug($p['project_name'], $l['id'])."-".$p['project_name'];
						break;
					case 'article':
						$loc[0] = base_url().$l['lang_shortcut']."/article/view/".get_lang_slug($p['blog_name'], $l['id'])."-".$p['blog_name'];
						break;
				}
				foreach ($loc as $tmp_loc){
					$data = array(
							'loc' => $tmp_loc,
							'changefreq' => "monthly",
							'priority' => 0.5,
					);
					$map .= $this->build_url($data);
				}
			}
		}
		$map .= "</urlset>";
		write_file("./Sitemap.xml", $map);
	}
	
	public function build_url($data){
		$map = "<url>\n";
		$map .= "<loc>".$data['loc']."</loc>\n";
		$map .= "<changefreq>".$data['changefreq']."</changefreq>\n";
		$map .= "<priority>".$data['priority']."</priority>\n";
		$map .= "</url>\n";
		return $map;
	}
}