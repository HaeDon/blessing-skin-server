<?php

return [
    /**
     * DataTables search options.
     */
    'search'          => [
        /**
         * Smart search will enclose search keyword with wildcard string "%keyword%".
         * SQL: column LIKE "%keyword%"
         */
        'smart'            => true,

        /**
         * Case insensitive will search the keyword in lower case format.
         * SQL: LOWER(column) LIKE LOWER(keyword)
         */
        'case_insensitive' => true,

        /**
         * Wild card will add "%" in between every characters of the keyword.
         * SQL: column LIKE "%k%e%y%w%o%r%d%"
         */
        'use_wildcards'    => false,
    ],

    /**
     * DataTables fractal configurations.
     */
    'fractal'         => [
        /**
         * Request key name to parse includes on fractal.
         */
        'includes'   => 'include',

        /**
         * Default fractal serializer.
         */
        'serializer' => 'League\Fractal\Serializer\DataArraySerializer',
    ],

    /**
     * DataTables internal index id response column name.
     */
    'index_column'    => 'DT_Row_Index',
];
