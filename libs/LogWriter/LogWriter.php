<?php

class LogWriter extends \Slim\Middleware{

	public function __construct($e)
    {
        if(config('app.debug') && $e){
            $log = app()->getLog(); // Force Slim to append log to env if not already
            $env = app()->environment();
            $env['slim.log'] = $log;
            $env['slim.log']->error($e);
            app()->contentType('text/html');
            app()->response()->status(500);
            app()->response()->body($this->renderBody($env, $e));
        }
    }

    /**
     * Call
     */
    public function call()
    {
        // $this->next->call();
    }

    /**
     * Render response body
     * @param  array      $env
     * @param  \Exception $exception
     * @return string
     */
    protected function renderBody(&$env, $exception)
    {
        $title = 'Slim Application Error';
        $code = $exception->getCode();
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();
        $html = "";
        if(config('app.debug_level') == 'hard') $html = sprintf('<h1>%s</h1>', $title);
        if(config('app.debug_level') == 'hard') $html .= '<p>The application could not run because of the following error:</p>';
        if(config('app.debug_level') == 'hard') $html .= '<h2>Details</h2>';
        if(config('app.debug_level') == 'soft') $html .= '<h2 style="margin:5px 0px;">Application Error Details:</h2>';
        $html .= sprintf('<div><strong>Type:</strong> %s</div>', get_class($exception));
        if ($code) {
            $html .= sprintf('<div><strong>Code:</strong> %s</div>', $code);
        }
        if ($message) {
            $html .= sprintf('<div><strong>Message:</strong> %s</div>', $message);
        }
        if ($file) {
            $html .= sprintf('<div><strong>File:</strong> %s</div>', $file);
        }
        if ($line) {
            $html .= sprintf('<div><strong>Line:</strong> %s</div>', $line);
        }
        if ($trace && config('app.debug_level') == 'hard') {
            $html .= '<h2>Trace</h2>';
            $html .= sprintf('<pre>%s</pre>', $trace);
        }

        return sprintf("<html><head><title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{display:inline-block;width:65px;}</style></head><body>%s</body></html>", $title, $html);
    }
}