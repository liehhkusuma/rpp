<?php
class Paginator implements IteratorAggregate{

    protected static $_instance;

    protected $getBasePage = "";
    private $getPerPage = 10;
    private $getNumLink = 3;
    private $getCurrentPage = "";
    private $getTotal = "";
    private $getPage = 1;
    private $numPage = 1;
    public $data = "";
    private $count = "";

    public static function instance() {
        static $initialized = FALSE;

        if ( ! $initialized) {
            self::$_instance = new Paginator();
            $initialized = TRUE;
        }

        return self::$_instance;
    }

    static function config($app, $perpage, $numLink){
        $self = self::instance();
        $basepage = route(app()->getRouteName());
        if(!$self->getBasePage) $self->getBasePage = $basepage;
        $self->getPerPage = $perpage ? $perpage : config('default.pagination');
        $self->getPage = $basepage != request_url() ? trim(str_replace($basepage, "", request_url()), '/') : 1;
        $self->numPage = ($self->getPage - 1) * $self->getPerPage;
        if($numLink) $self->getNumLink = $numLink;
        $self->getTotal = $app->count();
        $self->getCurrentPage = $basepage != request_url() ? trim(str_replace($basepage, "", request_url()), '/') : 1;
        $self->data = $app->skip($self->getPerPage*($self->getCurrentPage-1))->take($self->getPerPage)->get();
        $self->count = count($self->data);
        return $self;
    }

    public function __call($name, $arg){
        if(isset($this->$name)) return $this->$name;
        return $this->$name();
    }

    public function setData($key, $data){
        foreach ($data as $key1 => $value) {
            $this->data[$key][$key1] = $value;
        }
    }

    static function setBasePage($page){
        $self = self::instance();
        $self->getBasePage = $page;
    }

    public function links(){
        /* Page Amount */
        $page_amount = ceil($this->getTotal / $this->getPerPage);
        $basepage = $this->getBasePage;
        $numlink = $this->getNumLink;

        /* Keep HTTP Build Query */
        $query = http_build_query($_GET);
        $query = $query != '' ? '?' . $query : '';
        
        /* Get current page */
        $page = $this->getPage;
        $prev = $page - 1;  
        $next = $page + 1;
        $url = "";

        if($page_amount > 1)
        {   
            $url .= '<ul class="pagination nomargin">';
            //fisrt button
            if ($page > (1+$numlink))
                $url .= '<li><a href="'.$basepage.'/1'.$query.'">&laquo;</a></li>';
            //previous button
            $page > 1 ?
                $url .= '<li class="prev"><a href="'.$basepage.'/'.$prev.$query.'">&lsaquo;</a></li>':
                $url .= '<li class="disabled"><span>&lsaquo;</span></li>';
        
                for ($counter = $page-$numlink; $counter <= $page + $numlink; $counter++)
                {
                    if(!($counter<=0)&&!($counter>$page_amount)){
                        if($page == $counter){
                            $url .= '<li class="active"><span>';
                            $url .= $counter;
                            $url .= '</span></li>';
                        }else{
                            $url .= '<li><a href='.$basepage.'/'.$counter.$query.'>';
                            $url .= $counter;
                            $url .= '</a></li>';
                        }
                        
                    }
                }

            //Next button
            $page < $page_amount ?
                $url .= '<li class="next"><a href="'.$basepage.'/'.$next.$query.'">&rsaquo;</a></li>':
                $url .= '<li class="disabled"><span>&rsaquo;</span></li>';
            //Last button
            if ($page < $page_amount - $numlink)
                $url .= '<li><a href="'.$basepage.'/'.$page_amount.$query.'">&raquo;</a></li>';
        
            $url .= "</ul>";
        }
        
        return $url;
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator( $this->data->toArray() );
    }
}