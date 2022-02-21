<?php

namespace Core;

/**
 * View
 *
 * PHP version 7.4.12
 */
class View
{
	public static function returnViewsFolder($whichLang)
	{
		return $mainViewsFolder = '../App/Views/'.$whichLang.'/';
	}

    /**
     * Render a view file
     *
     * @param string $view  The view file
     *
     * @return void
     */
    public static function render($view, $args= [], $whichLang = 'EN')
    {
		extract($args, EXTR_SKIP);
		
        $file = static::returnViewsFolder($whichLang, $className).$view;  // relative to Core directory
		echo $file;
		exit();
        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }
	
	/**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
	public static function renderTemplate(string $template, array $args = [], $whichLang = 'EN')
    {
        echo static::getTemplate($template, $args, $whichLang);
    }
	
	/**
     * Get the contents of a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return string
     */
	public static function getTemplate(string $template, array $args = [], $whichLang = 'EN')
    {
        static $twig = null;
 
        if ($twig === null)
        {
            $loader = new \Twig\Loader\FilesystemLoader(static::returnViewsFolder($whichLang));
            $twig = new \Twig\Environment($loader);
			$twig->addGlobal('current_user', \App\Auth::getUser());
			$twig->addGlobal('flash_messages', \App\Flash::getMessages());
        }
 
        return $twig->render($template, $args, $whichLang);
    }
}
