<?php

/**
 *	Section Setter TagHandler
 *
 *	The contents of this file are subject to the terms of the GNU General
 *	Public License Version 3.0. You may not use this file except in
 *	compliance with the license. Any of the license terms and conditions
 *	can be waived if you get permission from the copyright holder.
 *
 *	Copyright (c) 2022 ~ ikkez
 *	Christian Knuth <ikkez0n3@gmail.com>
 *
 *	@version: 1.1.0
 *	@date: 24.08.2022
 *
 */

namespace Template\Tags;

class Inject extends \Template\TagHandler {

	function process($node) {
		if (isset($node['@attrib'])) {
			$attr = (array) $node['@attrib'];
			unset($node['@attrib']);
		} else
			$attr=[];
		return $this->build($attr,$node);
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
		} else {
			return '';
		}
		$mode = isset($attr['mode']) ? $attr['mode'] : 'overwrite';

		if (array_key_exists('local', $attr)) {
			$content = '$this->resolve('.var_export($content,true).', get_defined_vars(), 0, FALSE, FALSE)';
		} else {
			$content = var_export($this->resolveContent($content),true);
		}
		switch ($mode) {
			case 'prepend':
				$out = '$this->fw->set(\'template_sections.'.$id.'.content\','.$content
					.'.$this->fw->get(\'template_sections.'.$id.'.content\')'.');';
				break;
			case 'append':
				$out = '$this->fw->concat(\'template_sections.'.$id.'.content\','.$content.');';
				break;
			case 'overwrite':
			default:
				$out = '$this->fw->set(\'template_sections.'.$id.'.content\','.$content.');';
				break;
		}
		if (isset($attr['tag']))
			$out.= '$this->fw->set(\'template_sections.'.$id.'.tag\','.var_export($attr['tag'],true).');';
		unset($attr['mode'],$attr['tag'],$attr['section'],$attr['id']);
		if (!empty($attr))
			$out.= '$this->fw->merge(\'template_sections.'.$id.'.attr\','.var_export($attr,true).',TRUE);';
		return '<?php '.$out.'?>';
	}
}
