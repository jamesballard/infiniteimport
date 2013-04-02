<?php

class IdManager {

	/**
	 * Convert a local application/system id.
	 */
	public static function fromApplication($type, $sysid, $options = array()) {
		if (empty($sysid)) return null;
		
		$sysid_field = 'sysid';
		if (array_key_exists('field', $options)) $sysid_field = $options['field'];
		
		$create = false;
		if (array_key_exists('create', $options)) $create = $options['create'];

		$key = IdManager::cacheKey($type, $sysid);
		$id = apc_fetch($key);
		if ($id === false) {
			$id = query_value("select id from $type where $sysid_field = ?", array($sysid));
			
			if ($id === false && $create) {
				sql_execute("insert into $type set $sysid_field = ?", array($sysid));
				$id = query_value("select id from $type where $sysid_field = ?", array($sysid));
			}
			
			if ($id === false) {
				print "Warning $type not found with id $sysid\n";
				$id = null;
			} else {
				apc_add($key, $id);
			}
		}
		
		#print "Debug translated $type $sysid to $id\n";
		return $id;
	}

	public static function toApplication($type, $id, $options = array()) {
		$sysid_field = 'sysid';
		if (array_key_exists('field', $options)) $sysid_field = $options['field'];
		
		$sysid = query_value("select $sysid_field from $type where id = ?", array($id));
		return $sysid;
	}

	private static function cacheKey($type, $sysid) {
		$system_id = get_system_id();
		return "ir_${system_id}_${type}_${sysid}";
	}

	private function __construct() {
	}

}

?>
