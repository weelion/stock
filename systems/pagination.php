<?php

/**
 * 分页类
 *
 * @author shaoqi
 * @via    深圳市木槿软件工作室
 * @email  shaoqisq123@gmail.com
 *
 * @service 承接网站，跨平台软件，移动端Android、ios软件游戏制作
 */

class Pagination
{

    // 设置缺省初始化一些私有变量
    private $_properties = array(

        'avoid_duplicate_content'   =>  true,
        'method'                    =>  'get',
        'next'                      =>  '下一页',
        'page'                      =>  1,
        'page_set'                  =>  false,
        'preserve_query_string'     =>  0,
        'previous'                  =>  '上一页',
        'records'                   =>  '',
        'records_per_page'          =>  '',
        'reverse'                   =>  false,
        'selectable_pages'          =>  2,
        'total_pages'               =>  0,
        'trailing_slash'            =>  true,
        'variable_name'             =>  'page',
        'show_first'                =>  true,
        'first'                     =>  '首页',
        'show_last'                 =>  true,
        'last'                      =>  '末页'

    );

    /**
     *  构造函数.
     *
     *  初始化类的默认属性.
     *
     *  @return void
     */
    function __construct()
    {

        // 设置默认的URL
        $this->base_url();

    }


    /**
     *  When you first access a page with navigation you have the same content as you have when you access the first page
     *  from navigation. For example http://www.mywebsite.com/list will have the same content as http://www.mywebsite.com/list?page=1.
     *
     *  From a search engine's point of view these are 2 distinct URLs having the same content and your pages will be
     *  penalized for that.
     *
     *  So, by default, in order to avoid this, the library will have for the first page (or last, if you are displaying
     *  links in {@link reverse() reverse} order) the same path as you have for when you are accessing the page for the
     *  first (unpaginated) time.
     *
     *  If you want to disable this behaviour call this method with its argument set to FALSE.
     *
     *  <code>
     *  // don't avoid duplicate content
     *  $pagination->avoid_duplicate_content(false);
     *  </code>
     *
     *  @param  boolean     $avoid_duplicate_content    (Optional) If set to FALSE, the library will have for the first
     *                                                  page (or last, if you are displaying links in {@link reverse() reverse}
     *                                                  order) a different path than the one you have when you are accessing
     *                                                  the page for the first (unpaginated) time.
     *
     *                                                  Default is TRUE.
     *
     *  @return void
     *
     *  @since  2.0
     */
    function avoid_duplicate_content($avoid_duplicate_content = true)
    {

        // set property
        $this->_properties['avoid_duplicate_content'] = $avoid_duplicate_content;

    }

    public function base_url($base_url = '', $preserve_query_string = true)
    {

        // set the base URL
        $base_url = ($base_url == '' ? $_SERVER['REQUEST_URI'] : $base_url);

        // parse the URL
        $parsed_url = parse_url($base_url);

        // cache the "path" part of the URL (that is, everything *before* the "?")
        $this->_properties['base_url'] = $parsed_url['path'];

        // cache the "query" part of the URL (that is, everything *after* the "?")
        $this->_properties['base_url_query'] = isset($parsed_url['query']) ? $parsed_url['query'] : '';

        // store query string as an associative array
        parse_str($this->_properties['base_url_query'], $this->_properties['base_url_query']);

        // should query strings (other than those set in $base_url) be preserved?
        $this->_properties['preserve_query_string'] = $preserve_query_string;

    }

    /**
     *  Returns the current page's number.
     *
     *  <code>
     *  // echoes the current page
     *  echo $pagination->get_page();
     *  </code>
     *
     *  @return integer     Returns the current page's number
     */
    public function get_page()
    {

        // unless page was not specifically set through the "set_page" method
        if (!$this->_properties['page_set']) {

            // if
            if (

                // page propagation is SEO friendly
                $this->_properties['method'] == 'url' &&

                // the current page is set in the URL
                preg_match('/\b' . preg_quote($this->_properties['variable_name']) . '([0-9]+)\b/i', $_SERVER['REQUEST_URI'], $matches) > 0

            )

                // set the current page to whatever it is indicated in the URL
                $this->set_page((int)$matches[1]);

            // if page propagation is done through GET and the current page is set in $_GET
            elseif (isset($_GET[$this->_properties['variable_name']]))

                // set the current page to whatever it was set to
                $this->set_page((int)$_GET[$this->_properties['variable_name']]);

        }

        // if showing records in reverse order we must know the total number of records and the number of records per page
        // *before* calling the "get_page" method
        if ($this->_properties['reverse'] && $this->_properties['records'] == '') trigger_error('When showing records in reverse order you must specify the total number of records (by calling the "records" method) *before* the first use of the "get_page" method!', E_USER_ERROR);

        if ($this->_properties['reverse'] && $this->_properties['records_per_page'] == '') trigger_error('When showing records in reverse order you must specify the number of records per page (by calling the "records_per_page" method) *before* the first use of the "get_page" method!', E_USER_ERROR);

        // get the total number of pages
        $this->_properties['total_pages'] = $this->get_pages();

        // if there are any pages
        if ($this->_properties['total_pages'] > 0) {

            // if current page is beyond the total number pages
            /// make the current page be the last page
            if ($this->_properties['page'] > $this->_properties['total_pages']) $this->_properties['page'] = $this->_properties['total_pages'];

            // if current page is smaller than 1
            // make the current page 1
            elseif ($this->_properties['page'] < 1) $this->_properties['page'] = 1;

        }

        // if we're just starting and we have to display links in reverse order
        // set the first to the last one rather then first
        if (!$this->_properties['page_set'] && $this->_properties['reverse']) $this->set_page($this->_properties['total_pages']);

        // return the current page
        return $this->_properties['page'];

    }

    /**
     *  Returns the total number of pages, based on the total number of records and the number of records to be shown
     *  per page.
     *
     *  <code>
     *  // get the total number of pages
     *  echo $pagination->get_pages();
     *  </code>
     *
     *  @since  2.1
     *
     *  @return integer     Returns the total number of pages, based on the total number of records and the number of
     *                      records to be shown per page.
     */
    public function get_pages()
    {

        // return the total number of pages based on the total number of records and number of records to be shown per page
        return @ceil($this->_properties['records'] / $this->_properties['records_per_page']);

    }

    /**
     *  Change the labels for the "previous page" and "next page" links.
     *
     *  <code>
     *  // change the default labels
     *  $pagination->labels('Previous', 'Next');
     *  </code>
     *
     *  @param  string  $previous   (Optional) The label for the "previous page" link.
     *
     *                              Default is "Previous page".
     *
     *  @param  string  $next       (Optional) The label for the "next page" link.
     *
     *                              Default is "Next page".
     *  @return void
     *
     *  @since  2.0
     */
    public function labels($previous = '上一页', $next = '下一页')
    {

        // set the labels
        $this->_properties['previous'] = $previous;
        $this->_properties['next'] = $next;

    }

    /**
     *  Set the method to be used for page propagation.
     *
     *  <code>
     *  // set the method to the SEO friendly way
     *  $pagination->method('url');
     *  </code>
     *
     *  @param  string  $method     (Optional) The method to be used for page propagation.
     *
     *                              Values can be:
     *
     *                              - <b>url</b> - page propagation is done in a SEO friendly way;
     *
     *                              This method requires the {@link http://httpd.apache.org/docs/current/mod/mod_rewrite.html mod_rewrite}
     *                              module to be enabled on your Apache server (or the equivalent for other web servers);
     *
     *                              When using this method, the current page will be passed in the URL as
     *                              <i>http://youwebsite.com/yourpage/[variable name][page number]/</i> where
     *                              <i>[variable name]</i> is set by {@link variable_name()} and <i>[page number]</i>
     *                              represents the current page.
     *
     *                              - <b>get</b> - page propagation is done through GET;
     *
     *                              When using this method, the current page will be passed in the URL as
     *                              <i>http://youwebsite.com/yourpage?[variable name]=[page number]</i> where
     *                              <i>[variable name]</i> is set by {@link variable_name()} and <i>[page number]</i>
     *                              represents the current page.
     *
     *                              Default is "get".
     *
     *  @returns void
     */
    public function method($method = 'get')
    {

        // set the page propagation method
        $this->_properties['method'] = (strtolower($method) == 'url' ? 'url' : 'get') ;

    }

    /**
     *  Sets the total number of records that need to be paginated.
     *
     *  Based on this and on the value of {@link records_per_page()}, the script will know how many pages there are.
     *
     *  The total number of pages is given by the fraction between the total number records (set through {@link records()})
     *  and the number of records that are shown on a page (set through {@link records_per_page()}).
     *
     *  <code>
     *  // tell the script that there are 100 total records
     *  $pagination->records(100);
     *  </code>
     *
     *  @param  integer     $records    The total number of records that need to be paginated
     *
     *  @return void
     */
    public function records($records)
    {

        // the number of records
        // make sure we save it as an integer
        $this->_properties['records'] = (int)$records;

    }

    /**
     *  Sets the number of records that are displayed on one page.
     *
     *  Based on this and on the value of {@link records()}, the script will know how many pages there are: the total
     *  number of pages is given by the fraction between the total number of records and the number of records that are
     *  shown on one page.
     *
     *  <code>
     *  //  tell the class that there are 20 records displayed on one page
     *  $pagination->records_per_page(20);
     *  </code>
     *
     *  @param  integer     $records_per_page   The number of records displayed on one page.
     *
     *                      Default is 10.
     *
     *  @return void
     */
    public function records_per_page($records_per_page)
    {

        // the number of records displayed on one page
        // make sure we save it as an integer
        $this->_properties['records_per_page'] = (int)$records_per_page;

    }

    /**
     *  Generates the output.
     *
     *  <i>Make sure your script references the CSS file!</i>
     *
     *  <code>
     *  //  generate output but don't echo it
     *  //  but return it instead
     *  $output = $pagination->render(true);
     *  </code>
     *
     *  @param  boolean     $return_output      (Optional) Setting this argument to TRUE will instruct the script to
     *                                          return the generated output rather than outputting it to the screen.
     *
     *                                          Default is FALSE.
     *
     *  @return void
     */
    public function render($return_output = false)
    {

        // get some properties of the class
        $this->get_page();
        $now = $this->_properties['page']*$this->_properties['records_per_page'];
        //$output = '';
        $output = '<div class="dataTables_info" id="order_list_table_info" style="clear: none;">目前显示：'
                .($this->_properties['page']>1?(($this->_properties['page']-1)*$this->_properties['records_per_page']):($this->_properties['records']?1:0)).' - '.($now>$this->_properties['records']?$this->_properties['records']:$now).' 条, 共'.$this->_properties['total_pages'].' 页</div>';

        // if there is a single page, or no pages at all, don't display anything
        if ($this->_properties['total_pages'] > 1){
            // start building output
            $output .= '<div class="dataTables_paginate paging_full_numbers" id="order_list_table_paginate">';
            $output .= $this->_show_first() . $this->_show_previous() . $this->_show_pages() . $this->_show_next() . $this->_show_last();
            // finish generating the output
            $output .= '</div>';
        }
        // if $return_output is TRUE
        // return the generated content
        if ($return_output) return $output;

        // if script gets this far, print generated content to the screen
        echo $output;

    }

    /**
     *  默认情况下，分页的链接是在自然的顺序，从1的总页数.
     *
     *  与设置为true参数调用此方法会产生相反的顺序链接，从总页面数到1.
     *
     *
     *  @param  boolean     $reverse    (Optional) 设置为true产生逆序的导航链接。
     *
     *                                  Default is FALSE.
     *
     *  @return void
     *
     *  @since  2.0
     */
    public function reverse($reverse = false)
    {

        // set how the pagination links should be generated
        $this->_properties['reverse'] = $reverse;

    }

    /**
     *  设置要同时显示链接的数量（除了“上一页”和“下一页”的链接）
     *
     *  <code>
     *  // display links to 15 pages
     *  $pagination->selectable_pages(15);
     *  </code>
     *
     *  @param  integer     $selectable_pages   The number of links to be displayed at once (besides the "previous page"
     *                                          and "next page" links).
     *
     *                                          <i>You should set this to an odd number so that the same number of links
     *                                          will be shown to the left and to the right of the current page.</i>
     *
     *                                          Default is 11.
     *
     *  @return void
     */
    public function selectable_pages($selectable_pages)
    {

        // the number of selectable pages
        // make sure we save it as an integer
        $this->_properties['selectable_pages'] = (int)$selectable_pages;

    }

    /**
     *  Sets the current page.
     *
     *  <code>
     *  // sets the fifth page as the current page
     *  $pagination->set_page(5);
     *  </code>
     *
     *  @param  integer     $page           The page's number.
     *
     *                                      A number lower than <b>1</b> will be interpreted as <b>1</b>, while a number
     *                                      greater than the total number of pages will be interpreted as the last page.
     *
     *                                      The total number of pages is given by the fraction between the total number
     *                                      records (set through {@link records()}) and the number of records that are
     *                                      shown on one page (set through {@link records_per_page()}).
     *
     *  @return void
     */
    public function set_page($page)
    {

        // set the current page
        // make sure we save it as an integer
        $this->_properties['page'] = (int)$page;

        // if the number is lower than one
        // make it '1'
        if ($this->_properties['page'] < 1) $this->_properties['page'] = 1;

        // set a flag so that the "get_page" method doesn't change this value
        $this->_properties['page_set'] = true;

    }

    /**
     *  Enables or disabled trailing slash on the generated URLs when {@link method} is "url".
     *
     *  Read more on the subject on {@link http://googlewebmastercentral.blogspot.com/2010/04/to-slash-or-not-to-slash.html Google Webmasters's official blog}.
     *
     *  <code>
     *  // disables trailing slashes on generated URLs
     *  $pagination->trailing_slash(false);
     *  </code>
     *
     *  @param  boolean     $enabled    (Optional) Setting this property to FALSE will disable trailing slashes on generated
     *                                  URLs when {@link method} is "url".
     *
     *                                  Default is TRUE (trailing slashes are enabled by default).
     *
     *  @return void
     */
    public function trailing_slash($enabled)
    {

        // set the state of trailing slashes
        $this->_properties['trailing_slash'] = $enabled;

    }

    /**
     *  Sets the variable name to be used for page propagation.
     *
     *  <code>
     *  //  sets the variable name to "foo"
     *  //  now, in the URL, the current page will be passed either as "foo=[page number]" (if method is "get") or
     *  //  as "/foo[page number]" (if method is "url")
     *  $pagination->variable_name('foo');
     *  </code>
     *
     *  @param  string  $variable_name      A string representing the variable name to be used for page propagation.
     *
     *                                      Default is "page".
     *
     *  @return void
     */
    public function variable_name($variable_name)
    {

        // set the variable name
        $this->_properties['variable_name'] = strtolower($variable_name);

    }

    /**
     *  生成的链接的网页作为参数。
     *
     *  @access private
     *
     *  @return void
     */
    private function _build_uri($page)
    {

        // if page propagation method is through SEO friendly URLs
        if ($this->_properties['method'] == 'url') {

            // see if the current page is already set in the URL
            if (preg_match('/\b' . $this->_properties['variable_name'] . '([0-9]+)\b/i', $this->_properties['base_url'], $matches) > 0) {

                // build string
                $url = str_replace('//', '/', preg_replace(

                    // replace the currently existing value
                    '/\b' . $this->_properties['variable_name'] . '([0-9]+)\b/i',

                    // if on the first page, remove it in order to avoid duplicate content
                    ($page == 1 ? '' : $this->_properties['variable_name'] . $page),

                    $this->_properties['base_url']

                ));

            // if the current page is not yet in the URL, set it, unless we're on the first page
            // case in which we don't set it in order to avoid duplicate content
            } else $url = rtrim($this->_properties['base_url'], '/') . '/' . ($this->_properties['variable_name'] . $page);

            // handle trailing slash according to preferences
            $url = rtrim($url, '/') . ($this->_properties['trailing_slash'] ? '/' : '');

            // if values in the query string - other than those set through base_url() - are not to be preserved
            // preserve only those set initially
            if (!$this->_properties['preserve_query_string']) $query = implode('&', $this->_properties['base_url_query']);

            // otherwise, get the current query string
            else $query = $_SERVER['QUERY_STRING'];

            // return the built string also appending the query string, if any
            return $url . ($query != '' ? '?' . $query : '');

        // if page propagation is to be done through GET
        } else {

            // if values in the query string - other than those set through base_url() - are not to be preserved
            // preserve only those set initially
            if (!$this->_properties['preserve_query_string']) $query = $this->_properties['base_url_query'];

            // otherwise, get the current query string, if any, and transform it to an array
            else parse_str($_SERVER['QUERY_STRING'], $query);

            // if we are avoiding duplicate content and if not the first/last page (depending on whether the pagination links are shown in natural or reversed order)
            if (!$this->_properties['avoid_duplicate_content'] || ($page != ($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1)))

                // add/update the page number
                $query[$this->_properties['variable_name']] = $page;

            // if we are avoiding duplicate content, don't use the "page" variable on the first/last page
            elseif ($this->_properties['avoid_duplicate_content'] && $page == ($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1))

                unset($query[$this->_properties['variable_name']]);

            // make sure the returned HTML is W3C compliant
            return htmlspecialchars(html_entity_decode($this->_properties['base_url']) . (!empty($query) ? '?' . urldecode(http_build_query($query)) : ''));

        }

    }

    /**
     *  生成的“下一页”的链接
     *
     *  @access private
     */
    private function _show_next()
    {
        $output = '<a href="' .
                ($this->_properties['page'] == $this->_properties['total_pages'] ? 'javascript:void(0)' : $this->_build_uri($this->_properties['page'] + 1)) .
                '" class="next paginate_button' . ($this->_properties['page'] == $this->_properties['total_pages'] ? ' paginate_button_disabled' : '') . '"' .
                ' rel="next" id="order_list_table_next" tabindex="0"' .
                '>' . $this->_properties['next'] . '</a>';

        return $output;

    }

    /**
     *  产生分页的链接（减去“下一页”和“上一页”）
     *
     *  @access private
     */
    private function _show_pages()
    {

        $output = '';
        if($this->_properties['total_pages']<2){
            return $output;
        }
        $start = (($this->_properties['page'] - $this->_properties['selectable_pages']) > 0) ? $this->_properties['page'] - $this->_properties['selectable_pages'] : 1;
        $end = (($this->_properties['page'] + $this->_properties['selectable_pages']) < $this->_properties['total_pages']) ? (($this->_properties['page'] + $this->_properties['selectable_pages'])>5?($this->_properties['page'] + $this->_properties['selectable_pages']):5): $this->_properties['total_pages'];
        $start = ($end-$start)>=5?$start:($end-4);
        $start = $start<1?1:$start;
        $output.='<span>';
        for($i = $start; $i <= $end; $i++)
        {
            $output.= '<a tabindex="0" ';
            if ($this->_properties['page'] == $i)
            {
                $output.= 'class="paginate_active" href="javascript:void(0)"';
            }
            else
            {
                $output.= 'class="paginate_button" href="'.$this->_build_uri($i).'"';
            }
            $output.= '>'.$i.'</a>';
        }
        $output.='</span>';
        // return the resulting string
        return $output;

    }

    /**
     *  生成的“上一页”的链接.
     *
     *  @access private
     */
    private function _show_previous()
    {

        $output = '<a href="' .
                ($this->_properties['page'] == 1 ? 'javascript:void(0)' : $this->_build_uri($this->_properties['page'] - 1)) .
                '" class="paginate_button ' . 
                ($this->_properties['page'] == 1 ? ' paginate_button_disabled' : '') . '"' .
                ' rel="prev"' .

                '>' . $this->_properties['previous'] . '</a>';
        return $output;
    }

    /**
     *  生成的“首页”的链接.
     *
     *  @access private
     */
    private function _show_first()
    {
        $output ='';

        if (!$this->_properties['show_first']) {
            return $output;
        }else{
            $output.='<a tabindex="0" class="first paginate_button';
            if ($this->_properties['total_pages']>0 && $this->_properties['page']>1){
                $output.='" href="'.$this->_build_uri(1).'"';
            }else{
                $output.=' paginate_button_disabled" href="javascript:void(0)"';
            }
            $output.=' id="order_list_table_first">'.$this->_properties['first'].'</a>';

            return $output;
        }
    }

    /**
     *  生成的“末页”的链接.
     *
     *  @access private
     */
    private function _show_last()
    {
        $output ='';

        if (!$this->_properties['show_last']) {
            return $output;
        }else{
            $output.='<a tabindex="0" class="last paginate_button';
            if ($this->_properties['total_pages'] > $this->_properties['page'] && $this->_properties['total_pages']>0){
                $output.='" href="'.$this->_build_uri($this->_properties['total_pages']).'"';
            }else{
                $output.=' paginate_button_disabled" href="javascript:void(0)"';
            }
            $output.=' id="order_list_table_last">'.$this->_properties['last'].'</a>';

            return $output;
        }
    }

}

?>