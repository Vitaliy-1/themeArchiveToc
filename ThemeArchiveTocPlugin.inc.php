<?php

/**
 * @file plugins/themes/themeArchiveToc/ThemeArchiveTocPlugin.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University Library
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ThemeArchiveTocPlugin
 * @ingroup plugins_themes_themeArchiveToc
 *
 * @brief child theme to display issue TOC on the archive page
 */
import('lib.pkp.classes.plugins.ThemePlugin');

class ThemeArchiveTocPlugin extends ThemePlugin {
	/**
	 * Initialize the theme's styles, scripts and hooks. This is only run for
	 * the currently active theme.
	 *
	 * @return null
	 */
	public function init() {
		$this->setParent('defaultmanuscriptchildthemeplugin');
		HookRegistry::register ('TemplateManager::display', array($this, 'archiveToc'));
	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.themes.themeArchiveToc.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return __('plugins.themes.themeArchiveToc.description');
	}

	function archiveToc($hookname, $args) {
		$templateMgr = $args[0];
		$template = $args[1];

		if ($template !== 'frontend/pages/issueArchive.tpl') return false;

		$issues = $templateMgr->getTemplateVars("issues");
		$publishedArticlesArchive = array();
		$publishedArticleDao = DAORegistry::getDAO("PublishedArticleDAO");
		foreach ($issues as $key => $issue) {
			$publishedArticlesInSections = $publishedArticleDao->getPublishedArticlesInSections($issue->getId());
			$publishedArticlesArchive[$issue->getId()] = $publishedArticlesInSections;
		}

		$templateMgr->assign("publishedArticlesArchive", $publishedArticlesArchive);
	}
}
