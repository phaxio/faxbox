<?php

class BaseController extends Controller {

    protected $layout = 'layouts.main';
    
    public function __construct()
    {
        $this->beforeFilter('csrf', [ 'on' => 'post' ]);
    }
    
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    /**
     * Set the specified view as content on the layout.
     *
     * @param  string  $path
     * @param  array  $data
     * @return void
     */
    protected function view($path, $data = [])
    {
        $this->layout->content = View::make($path, $data);
    }

}
