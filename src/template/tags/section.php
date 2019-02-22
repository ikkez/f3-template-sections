<?php
/**
 *	Section TagHandler
 *
 *	The contents of this file are subject to the terms of the GNU General
 *	Public License Version 3.0. You may not use this file except in
 *	compliance with the license. Any of the license terms and conditions
 *	can be waived if you get permission from the copyright holder.
 *
 *	Copyright (c) 2019 ~ ikkez
 *	Christian Knuth <ikkez0n3@gmail.com>
 *
 *	@version: 1.0.0
 *	@date: 28.01.2019
 *
 */

namespace Template\Tags;

class Section extends \Template\TagHandler {

	protected $default_tag = 'section';

	/**
	 * default tag getter
	 * @return string
	 */
	function getDefaultTag() {
		return $this->default_tag;
	}

	/**
	 * default tag setter
	 * @param $tag
	 */
	function setDefaultTag($tag) {
		$this->default_tag = $tag;
	}

	/**
	 * initialize plugin and register afterrenderer
	 * @param $name
	 * @param \Template|NULL $tmpl
	 * @param array $args
	 */
	static public function init($name,\Template $tmpl=NULL,$args=[]) {
		if (!$tmpl)
			$tmpl = \Template::instance();
		if (!empty($args) && is_array($args))
			$obj = static::instance($args);
		else
			$obj = static::instance();
		$obj->tmpl = $tmpl;
		$tmpl->extend($name,[$obj,'process']);

		// TODO: optimize this in TagHandler and return $obj for further usage

		/** @var \Base $f3 */
		$f3 = \Base::instance();
		$f3->set('template_sections',[]);
		$tmpl->afterrender(function($data) use ($f3,$obj) {
			foreach($f3->get('template_sections') as $key=>$el)
				if (preg_match('<!--section:'.$key.'-->',$data)) {
					$tag=$obj->tmpl->resolve($el['content']);
				if (strtoupper($el['tag']) !== 'FALSE')
					$tag='<'.$el['tag'].' '.$obj->resolveParams($el['attr']).'>'
						.$tag.'</'.$el['tag'].'>';
					$data = preg_replace('/<!--section:'.$key.'-->(.*)<!--\/section-->/i',$tag, $data, 1);
				}
			return $data;
		});
	}

	/**
	 * build tag string
	 * @param array $attr
	 * @param string $content
	 * @return string
	 */
	function build($attr,$content) {
		$id = NULL;
		if (isset($attr['section'])) {
			$id=$attr['section'];
		} elseif (isset($attr['id'])) {
			$id=$attr['id'];
		}

		$php = '';
		if (!$this->f3->exists('template_sections.'.$id)) {
			$tag = isset($attr['tag']) ? $attr['tag'] : $this->default_tag;
			unset($attr['section']);
			$data = [
				'tag'=>$tag,
				'attr'=>$attr,
				'content'=>$content
			];
			$php.='$this->fw->set(\'template_sections.'.$id.'\','.var_export($data,true).')';
		}

		return '<!--section:'.$id.'--> <!--/section-->'.'<?php '.$php.'?>';
	}
}