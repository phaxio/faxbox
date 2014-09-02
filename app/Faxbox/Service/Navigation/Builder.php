<?php

namespace Faxbox\Service\Navigation;


use Illuminate\Config\Repository;
use Faxbox\Repositories\User\UserInterface;

class Builder
{
    /**
     * Config repository instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;
    
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * Create a new Navigation Builder instance.
     *
     * @param  \Illuminate\Config\Repository  $config
     * @return void
     */
    public function __construct(Repository $config, UserInterface $user)
    {
        $this->config = $config;
        $this->user = $user;
    }

    /**
     * Build the HTML navigation from the given config key.
     *
     * @param  string $url
     * @param  string $type
     * @return string
     */
    public function make($url = null)
    {
        $menu      = $this->getNavigationConfig();
        $html      = '';
        $url       = $url ?: \Request::path();
        
        foreach ($menu as $item) 
        {
            $html .= $this->buildItem($item, $url);
        }

        return $html;
    }
    
    protected function buildItem($item, $url)
    {
        if(!$this->hasAccess($item))
            return;
            
        $subHtml = '';
        if (array_key_exists('sub', $item))
        {
            foreach ($item['sub'] as $subItem)
            {
                $subHtml .= $this->buildItem($subItem, $url);
            }

            $subHtml = $this->wrapSub($subHtml);

        }

        $isActive = false;

        if ($this->isActiveItem($item, $url))
        {
            $isActive = $hasActive = true;
        }

        return $this->getNavigationItem($item, $isActive, $subHtml);
    }

    /**
     * Load the navigation config for the given type.
     *
     * @return array
     */
    protected function getNavigationConfig()
    {
        return $this->config->get('navigation');
    }

    /**
     * Determine whether the given item is currently active.
     * @param  array   $item
     * @param  string  $url
     * @return bool
     */
    protected function isActiveItem(array $item, $url)
    {
        foreach ($item['active'] as $active) {
            if (str_is($active, $url)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a parsed HTML navigation list item for the given item.
     *
     * @param  array  $item
     * @param  bool   $isActive
     * @return string
     */
    protected function getNavigationItem(array $item, $isActive, $subHtml = '')
    {
        $anchor = $this->getItemAnchor($item, $isActive, $subHtml);

        return $this->wrapAnchor($anchor, $isActive, $subHtml);
    }

    /**
     * Get the HTML anchor link for the given item.
     *
     * @param  array  $item
     * @return string
     */
    protected function getItemAnchor(array $item, $isActive, $subHtml)
    {
        $active = $isActive && !$subHtml ? 'active' : '';
        
        //$caret = $isActive && !$subHtml ? '<span class="fa arrow"></span>' : '';
        
        return link_to($item['route'], $item['label'], ['class' => $active]);
    }

    /**
     * Wrap the given anchor in a list item.
     *
     * @param  string  $anchor
     * @param  bool    $isActive
     * @return string
     */
    protected function wrapAnchor($anchor, $isActive, $subHtml = '')
    {
        $class = $isActive ? ' class="active"' : '';
        
        return '<li' . $class . '>' . $anchor . $subHtml . '</li>';
    }
    
    protected function wrapSub($subHtml)
    {
        return '<ul class="nav nav-second-level">'.$subHtml."</ul>";
    }

    protected function hasAccess($item)
    {
        $id = $this->user->loggedInUserId();
        
        // User not logged in
        if($id === null) 
            return false;
        
        // No access key set, so we'll assume all are allowed
        if(!array_key_exists('access', $item)) 
            return true;
        
        return $this->user->hasAccess($id, $item['access']);
    }
}