<?php
/**
 * Helper Functions.
 *
 * @package     Kutak/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Define a constant if it is not already defined.
 *
 * @since   1.0.0
 * @access  public
 * @param   string  $name   Constant name.
 * @param   mixed   $value  Value.
 */
function apalodi_maybe_define_constant( $name, $value ) {
    if ( ! defined( $name ) ) {
        define( $name, $value );
    }
}

/**
 * Determine if the current value should be excluded from string.
 *
 * @since   1.0
 * @access  public
 * @param   string $string String to search.
 * @param   array  $excluded_values Array of excluded values to search in the string.
 * @return  bool True if one of the excluded values was found, false otherwise
 */
function is_apalodi_excluded_values( $string, $excluded_values ) {
    foreach ( $excluded_values as $excluded_value ) {
        if ( strpos( $string, $excluded_value ) !== false ) {
            return true;
        }
    }
    return false;
}

/**
 * Parses the string into variables without the max_input_vars limitation.
 *
 * @since   1.0
 * @access  public
 * @param   string $string
 * @return  array $result
 */
function apalodi_parse_str( $string ) {

    if ( '' == $string ) {
        return false;
    }

    $result = array();
    $pairs = explode( '&', $string );

    foreach( $pairs as $key => $pair ) {

        // use the original parse_str() on each element
        parse_str( $pair, $params );

        $k = key( $params );

        if( ! isset( $result[$k] ) ) {
            $result+=$params;
        } else {
            $result[$k] = apalodi_array_merge_recursive( $result[$k], $params[$k] );
        }

    }

    return $result;
}

/**
 * Merge arrays without converting values with duplicate keys to arrays as array_merge_recursive does.
 *
 * As seen here http://php.net/manual/en/function.array-merge-recursive.php#92195
 *
 * @since   1.0
 * @access  public
 * @param   array $array1
 * @param   array $array2
 * @return  array $merged
 */
function apalodi_array_merge_recursive( array $array1, array $array2 ) {
    $merged = $array1;

    foreach( $array2 as $key => $value ) {

        if ( is_array( $value ) && isset( $merged[$key] ) && is_array( $merged[$key] ) ) {
            $merged[$key] = apalodi_array_merge_recursive( $merged[$key], $value );
        } else if ( is_numeric( $key ) && isset( $merged[$key] ) ) {
            $merged[] = $value;
        } else {
            $merged[$key] = $value;
        }
    }

    return $merged;
}

/**
 * Recursive wp_parse_args for multidimensional arrays.
 *
 * http://mekshq.com/recursive-wp-parse-args-wordpress-function/
 *
 * @since   1.0
 * @access  public
 * @param   array $args Value to merge with $defaults
 * @param   array $defaults Array that serves as the defaults.
 * @return  array $result Merged user defined values with defaults
 */
function apalodi_parse_args( $args, $defaults ) {
    $args = (array) $args;
    $defaults = (array) $defaults;
    $result = $defaults;
    foreach ( $args as $k => $v ) {
        if ( is_array( $v ) && isset( $result[ $k ] ) ) {
            $result[ $k ] = apalodi_parse_args( $v, $result[ $k ] );
        } else {
            $result[ $k ] = $v;
        }
    }
    return $result;
}

/**
 * Parse string like "title:Hello world|weekday:Monday" to array('title' => 'Hello World', 'weekday' => 'Monday')
 *
 * @since   1.0
 * @access  public
 * @param   string $value
 * @param   array $default
 * @return  array $result
 */
function apalodi_parse_multi_attribute( $value, $default = array() ) {
    $result = $default;
    $params_pairs = explode( '|', $value );
    if ( ! empty( $params_pairs ) ) {
        foreach ( $params_pairs as $pair ) {
            $param = preg_split( '/\:/', $pair );
            if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
                $result[ $param[0] ] = rawurldecode( $param[1] );
            }
        }
    }

    return $result;
}

/**
 * Convert array keys to a data attributes.
 *
 * @since   1.0
 * @param   array $columns
 * @param   string $type - Return array or string
 * @return  string|array $classes
 */
function apalodi_array_keys_to_data_attr( $data, $type = 'string' ) {
    
    $data_attr = array();

    if ( 'array' == $type ) {

        foreach ( $data as $key => $value ) {
            $data_attr['data-' . esc_attr( $key )] = esc_attr( $value );
        }

        return $data_attr;

    } else {

        foreach ( $data as $key => $value ) {
            $data_attr[] = 'data-' . esc_attr( $key ) . '="' .esc_attr( $value ) . '"';
        }

        return implode( ' ', $data_attr );
    }
}

/**
 * Convert columns number to a grid class names.
 *
 * @since   1.0
 * @param   array $columns
 * @param   string $type - Return array or string
 * @return  string|array $classes
 */
function apalodi_columns_to_css_columns( $columns, $type = 'string' ) {
    $classes = array(
        'col-xs-' . apalodi_dot_to_dash( round( 12 / $columns['mobile'], 1 ) ),
        'col-sm-' . apalodi_dot_to_dash( round( 12 / $columns['tablet'], 1 ) ),
        'col-md-' . apalodi_dot_to_dash( round( 12 / $columns['medium'], 1 ) ),
        'col-lg-' . apalodi_dot_to_dash( round( 12 / $columns['desktop'], 1 ) )
    );
    $classes = array_map( 'esc_attr', $classes );

    if ( 'array' == $type ) {
        return $classes;
    } else {
        return implode( ' ', $classes );
    }
}

/**
 * Convert string to a valid css class name.
 *
 * @since   1.0
 * @param   string $class
 * @return  string
 */
function apalodi_build_safe_css_class( $class ) {
    return preg_replace( '/\W+/', '', strtolower( str_replace( ' ', '-', strip_tags( $class ) ) ) );
}

/**
 * Convert color value from hex to rgba.
 *
 * @since   1.0
 * @access  public
 * @param   string $hex - color with hex value
 * @param   string $opacity - opacity value
 * @param   string $fomat - what format to return the data
 * @return  array|string $rgba - an array or string with the rgba values
 */
function apalodi_hex_to_rgba( $hex, $opacity = '1', $format = 'string' ) {
    $hex = str_replace( '#', '', $hex );

    if( strlen( $hex ) == 3) {
        $r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }
    
    $rgba = array( $r, $g, $b, $opacity );
    
    if ( 'array' == $format ) {
        return $rgba;
    } else {
        return 'rgba('. implode( ',', $rgba ) .')'; // returns the rgba values separated by commas
    }

}

/**
 * Convert color value from rgb to hex.
 *
 * @since   1.0
 * @access  public
 * @param   string $rgb - color with rgb value
 * @return  string $hex - the hex value including the number sign (#)
 */
function apalodi_rgb_to_hex( $rgb ) {
    $hex = "#";
    $hex .= str_pad( dechex( $rgb[0]), 2, "0", STR_PAD_LEFT );
    $hex .= str_pad( dechex( $rgb[1]), 2, "0", STR_PAD_LEFT );
    $hex .= str_pad( dechex( $rgb[2]), 2, "0", STR_PAD_LEFT );
    return $hex;
}

/**
 * CHeck if its url.
 *
 * @since   1.0
 * @access  public
 * @param   string $url
 * @return  string|bool $url - false if it's not url
 */
function apalodi_validate_url( $url ) {

    // Must start with http:// or https://
    if ( 0 !== strpos( $url, 'http://' ) && 0 !== strpos( $url, 'https://' ) ) {
        return false;
    }

    // Must pass validation
    if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
        return false;
    }

    return true;

}

/**
 * Get opacity in valid format.
 *
 * @since   1.0
 * @access  public
 * @param   string $number Number between 0 and 100
 * @return  string $opacity
 */
function apalodi_number_to_opacity( $number ) {
    
    if ( $number >= '100' ) {
        $opacity = '1';
    }
    elseif ( $number <= '0' ) {
        $opacity = '0';
    }
    else {
        if ( $number < '10' ) {
            $number = '0' . $number;
        }
        $opacity = '0.'.$number;
    }
    return $opacity;
}

/**
 * Convert aa underscore to a dash in a string.
 *
 * @since   1.0
 * @access  public
 * @param   string $string
 * @return  string $string - Converted string
 */
function apalodi_underscore_to_dash( $string ) {
    return str_replace( '_', '-', $string );
}

/**
 * Convert a dash to an underscore in a string.
 *
 * @since   1.0
 * @access  public
 * @param   string $string
 * @return  string $string - Converted string
 */
function apalodi_dash_to_underscore( $string ) {
    return str_replace( '-', '_', $string );
}

/**
 * Convert a plus to a dash in a string.
 *
 * @since   1.0
 * @access  public
 * @param   string $string
 * @return  string $string - Converted string
 */
function apalodi_plus_to_dash( $string ) {
    return str_replace( '+', '-', $string );
}

/**
 * Convert a dot to a plus in a string.
 *
 * @since   1.0
 * @access  public
 * @param   string $string
 * @return  string $string - Converted string
 */
function apalodi_dot_to_dash( $string ) {
    return str_replace( '.', '-', $string );
}

/**
 * Convert a space to a plus in a string.
 *
 * @since   1.0
 * @access  public
 * @param   string $string
 * @return  string $string - Converted string
 */
function apalodi_space_to_plus( $string ) {
    return str_replace( ' ', '+', $string );
}

/**
 * Remove white space in string.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_remove_white_space( $str ) {

    $str = str_replace( "\t", ' ', $str );
    $str = str_replace( "\n", '', $str );
    $str = str_replace( "\r", '', $str );

    while ( stristr( $str, ' ') ) {
        $str = str_replace( ' ', '', $str );
    }

    return $str;
}

/**
 * Implode array keys with desired value.
 *
 * @since   1.0
 * @access  public
 * @param   array $array
 * @param   string $value Checked for this value
 * @param   string|array $remove Array key or keys to remove before implode
 * @param   string $before Value to prepend to array keys
 * @param   string $after Value to append to array keys
 * @return  array $tags
 */
function apalodi_implode_array_keys( $array, $value, $remove = false, $before = '', $after = '' ) {
    
    if ( is_array( $remove ) ) {
        foreach ( $remove as $key ) {
            unset( $array[$key] );
        }
    } elseif ( $remove ) {
        unset( $array[$remove] );
    }

    $new_array = array();
    
    foreach ( $array as $key => $item ) {
        if ( $array[$key] == $value ) {
            $new_array[$before.$key.$after] = $item;
        }
  
    }

    return implode( ' ', array_keys( $new_array ) );
}

/**
 * Return empty string.
 *
 * @since   1.0
 * @access  public
 * @return  string
 */
function apalodi_return_empty_string( ) {
    return '';
}
