<?php

class IdManager {

	/**
	 * Convert a local application/system id.
	 */
	public static function fromApplication($type, $sysid) {
		if (empty($sysid)) return null;

		$key = IdManager::cacheKey($type, $sysid);
		$id = apc_fetch($key);
		if ($id === false) {
			$id = query_value("select id from $type where sysid = ?", array($sysid));
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

	public static function toApplication($type, $id) {
		$sysid = query_value("select sysid from $type where id = ?", array($id));
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
