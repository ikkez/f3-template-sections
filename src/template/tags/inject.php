<?php

/**
 *	Section Setter TagHandler
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

class Inject extends \Template\TagHandler {

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
		switch ($mode) {
			case 'prepend':
				$out = '$this->fw->set(\'template_sections.'.$id.'.content\','.var_export($content,true)
					.'.$this->fw->get(\'template_sections.'.$id.'.content\')'.');';
				break;
			case 'append':
				$out = '$this->fw->concat(\'template_sections.'.$id.'.content\','.var_export($content,true).');';
				break;
			case 'overwrite':
			default:
				$out = '$this->fw->set(\'template_sections.'.$id.'.content\','.var_export($content,true).');';
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