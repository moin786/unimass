<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DatatablesLoader
{
    /**
     * Pull a particular property from each assoc. array in a numeric array,
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    static function pluck ( $a, $prop )
    {
        $out = array();

        for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            if(empty($a[$i][$prop])){
                continue;
            }
            //removing the $out array index confuses the filter method in doing proper binding,
            //adding it ensures that the array data are mapped correctly
            $out[$i] = $a[$i][$prop];
        }

        return $out;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    static function filter ( $request, $columns, &$bindings )
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck( $columns, 'dt' );

        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = strtoupper($request['search']['value']);

            for ( $i=0, $ien=count($columns) ; $i<$ien ; $i++ ) {
                $requestColumn = $columns[$i];

                if(!empty($columns[$i])){
                    if($columns[$i] != 'sl')
                        $globalSearch[] = "upper(".$columns[$i].") LIKE '%$str%'";
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if ( count( $globalSearch ) ) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }

        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
            implode(' AND ', $columnSearch) :
            $where .' AND '. implode(' AND ', $columnSearch);
        }

        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }

        return $where;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $sql  sql text
     *  @param  array $order_by_column name text
     *  @param  array $sorting_order as sort type text
     *  @param  array $columns information array
     *    sql_exec() function
     *  @return string SQL where clause
     */
    static function datatable( $request, $sql, $order_by_column, $sorting_order="DESC", $columns )
    {
        $bindings = array();

        $where = self::filter( $request, $columns, $bindings );
        //echo $where;die; 

        $limit = '';
        if ( isset($request['start']) && $request['length'] != -1 ) {
            $range = $request['start']+$request['length'];
            $limit = " limit ".intval($request['start']).",$range";
        }

        $order_by_cond  = ($order_by_column!="")?" order by IFNULL(b.created_at,now()) $sorting_order":"";
        //$order_by_cond  = "";
        //echo "select b.* from ($sql $where) b where $limit $order_by_cond";die;
        $data = DB::select("select b.* from ($sql $where) b $order_by_cond $limit ");
        $resfilterlength = count(DB::select($sql));
        $restotallength  = count($data);

        $recordsFiltered = $resfilterlength;

        $recordsTotal = $restotallength;

        return array(
            "draw"            => isset ( $request['draw'] ) ? intval( $request['draw'] ) : 0,
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data , $request['start'])
        );
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $sql  sql text
     *  @param  array $order_by_column name text
     *  @param  array $sorting_order as sort type text
     *  @param  array $columns information array
     *    sql_exec() function
     *  @return string SQL where clause
     */
    static function datatableCustom( $request, $sql, $order_by_column, $sorting_order="DESC", $columns )
    {
        $bindings = array();

        $where = self::filter( $request, $columns, $bindings );
        //echo $where;die; 

        $limit = '';
        if ( isset($request['start']) && $request['length'] != -1 ) {
            $range = $request['start']+$request['length'];
            $limit = " b.rownumber > ".intval($request['start'])." and b.rownumber <=$range";
        }

        //$order_by_cond  = ($order_by_column!="")?" order by nvl(b.au_update_at,b.au_entry_at) $sorting_order":"";
        $order_by_cond  = ($order_by_column!="")?" order by b.invoice_date $sorting_order":"";
        //$order_by_cond  = "";
        //echo "select b.* from ($sql $where) b where $limit $order_by_cond";die;
        $data = DB::select("select b.* from ($sql $where) b where $limit $order_by_cond");
        $resfilterlength = count(DB::select($sql));
        $restotallength  = count($data);

        $recordsFiltered = $resfilterlength;

        $recordsTotal = $restotallength;

        return array(
            "draw"            => isset ( $request['draw'] ) ? intval( $request['draw'] ) : 0,
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data , $request['start'])
        );
    }

    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    static function data_output ( $columns, $data, $range )
    {
        $out = array();
        $empty = "";
        if($range == 0)
            $sl = 1;
        else
            $sl = $range+1;

        //echo count($columns);die;
        for ( $i=0; $i<count($data); $i++ ) {
            $row = array();

            for ( $j=0; $j<count($columns);  $j++ ) {
                $column = $columns[$j];

                if($j==0)
                {
                    $row[ $j ] = $sl;
                }
                else
                {
                    $row[ $j ] = $data[$i]->{$columns[$j]};
                }
            }

            $out[] = $row;
            $sl++;
        }

        return $out;
    }
}