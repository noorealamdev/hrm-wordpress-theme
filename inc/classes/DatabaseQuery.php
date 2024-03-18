<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Hrm_DbQuery' ) ) {
	class Hrm_DbQuery {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			//
		}

		/**
		 * Get db table data
		 */
		public static function get_table_data( string $table_name ): ?array {
			global $wpdb;
			$table_name = $wpdb->prefix . $table_name;
			$results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC");

			if ( ! $results ) {
				return [];
			}
			return $results;
		}

		/**
		 * Get db table single row
		 */
		public static function get_table_single_row( string $table_name, int $row_id): ?object {
			global $wpdb;
			$table_name = $wpdb->prefix . $table_name;
			$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $row_id ) );

			if ( ! $result ) {
				return null;
			}
			return $result;
		}

		/**
		 * Get db table rows by where clause
		 */
		public static function get_table_rows_where( string $table_name, string $where_column, int $where_value): ?array {
			global $wpdb;
			$table_name = $wpdb->prefix . $table_name;
			$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE $where_column = $where_value ORDER BY created_at DESC");

			if ( ! $results ) {
				return [];
			}
			return $results;
		}

		/**
		 * Get db table specific filed value
		 */
		public static function get_table_field_value( string $table_name, string $column_name, int $row_id ): ?string {
			global $wpdb;
			$table_name = $wpdb->prefix . $table_name;
			$result     = $wpdb->get_row( "SELECT * from $table_name where ID = $row_id" );

			if ( ! $result ) {
				return null;
			}

			return $result->$column_name;
		}

	} // class end

	Hrm_DbQuery::instance()->initialize();
}