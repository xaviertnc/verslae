<?php

//NOTE:  NO NAMESPACE!  I.e. This class is in global scope and static.

use \Xap\Engine as Xap;

/**
 * App DB using XAP lib
 *
 * @author C. Moller <xavier.tnc@gmail.com> - 25 Nov 2014
 *
 * @updated 21 Feb 2016
 * 		- Added "paginate" and "first" methods.
 * 		- Added and updated comments.
 */
class DB
{

	public static function connect($connection)
	{
		Xap::exec([[
		'host' => $connection['DBHOST'],
		'database' => $connection['DBNAME'],
		'user' => $connection['DBUSER'],
		'password' => $connection['DBPASS']
		]]);
	}

	public static function objClassName($classname = null)
	{
		return Xap::exec([':classname', $classname]);
	}

	public static function setPagination($page, $rpp = null, $prevStr = 'Prev', $nextStr = 'Next')
	{
		$arguments = ['page' => $page, 'prev_string' => $prevStr, 'next_string' => $nextStr];
		if ($rpp) { $arguments['rpp'] = $rpp; }
		return Xap::exec([':pagination', $arguments]);
	}


	// The most basic XAP DB:: function.
	// Uses stock XAP short-command syntax and params layout. (See XAP Docs)
	//
	// NB: Only use this function if there is no custom DB:: function available
	// or the custom function doesn't accommodate your specific implementation.
	//
	// Examples:
	// DB::xap('users.12');
	// DB::xap('users LIMIT 30');
	// DB::xap('users:del WHERE id = ?', [2])
	public static function xap($cmd, $arguments = null)
	{
		return Xap::exec([$cmd, $arguments]);
	}

	// A subset of DB::xap focussing on use of RAW Query Syntax with optional pagination support.
	//
	// Use this function if you want to execute advanced queries not covered by XAP syntax or
	// an existing DB:: function
	//
	// If we require auto pagination, first do:
	// DB::setPagination($page, $itemspp);
	//
	// Then:
	// DB::exec('SELECT * FROM users', null, true);
	// DB::exec('SELECT * FROM users WHERE name=?', [$name], 'paginate');
	//
	// Complex Query:
	// DB::exec('SELECT (((complex field defs))).. FROM (((complex sources))).. WHERE (((complex conditions)))..', $args);
	public static function exec($sql, $arguments = null, $pagination = false)
	{
		$command = $pagination ? ':query/pagination ' : ':query ';
		return Xap::exec([$command . $sql, $arguments]);
	}

	// XAP syntax select command
	//
	// Examples:
	// DB::select('users.12');
	// DB::select('users LIMIT 30');
	// DB::select('users WHERE name=?', [$name]);
	// DB::select('users(id, name) WHERE name=?', [$name]);
	public static function select($query, $arguments = null)
	{
		return Xap::exec([$query, $arguments]);
	}

	// XAP syntax select command with AUTO pagination
	// NB: It goes without saying.. DON'T add a LIMIT clause to an auto paginated query!
	//
	// Examples:
	// DB::paginate('users.12', $page, $itemspp);
	// DB::paginate('users(id, name) WHERE name=?', [$name], $page, $itemspp, 'Before', 'After');
	public static function paginate($query, $arguments = null, $page = 1, $rpp = 7, $prevStr = 'Prev', $nextStr = 'Next')
	{
		static::setPagination($page, $rpp, $prevStr, $nextStr);
		return Xap::exec(["$query/pagination", $arguments]);
	}

	// DB::first('users', 'WHERE name=?', [$name]);
	// DB::first('users(id, name)', 'WHERE name=?', [$name]);
	public static function first($table, $conditions = '', $arguments = null)
	{
		return Xap::exec(["$table/first $conditions", $arguments]);
	}

	// $conditions == Conditions statement without values. e.g. 'WHERE id=?' - NB: Remember to put WHERE infront of your statement!
	// $arguments == Conditions statement values array. e.g. [12, 'Hello', 'World'] NOT DATA field-value pairs!
	public static function count($table, $conditions = '', $arguments = null)
	{
		return Xap::exec(["$table:count $conditions", $arguments]);
	}

	// $conditions == Conditions statement without values. e.g. 'WHERE id=?' - NB: Remember to put WHERE infront of your statement!
	// DB::exists('users'); check if ANY records exist
	// DB::exists('users', 'WHERE user_id = ? AND is_active = 1', [2]); check if SPECIFIC record/s exists
	// @return: boolean
	public static function exists($table, $conditions, $arguments = null)
	{
		return Xap::exec(["$table:exists $conditions", $arguments]);
	}

	// $conditions == Conditions statement without values. e.g. 'WHERE id=:id' - NB: Remember to put WHERE infront of your statement!
	// $data == field-value pairs array. e.g.  ['name'=>Johnny, 'age'=>29]
	// $arguments == Condition statement values array. e.g. [12, 'Hello', 'World'] NOT DATA field-value pairs!
	// DB::update('mytable', 'WHERE id=:id', $_POST_ARRAY, ['id' => $id]); NOTE: Named parameter(s) notation in conditional is required instead of positional notation!
	// @return: boolean ?
	public static function update($table, $conditions, $data = null, $arguments = null)
	{
		return Xap::exec(["$table:mod $conditions", $data, $arguments]);
	}

	// $data == field-value pairs array OR object. e.g.  ['name'=>'Johnny', 'age'=>29] OR $data->name == 'Johnny' etc.
	// @return == affected rows
	public static function insertInto($table, $data)
	{
		return Xap::exec(["$table:add", $data]);
	}

	public static function lastInsertId()
	{
		return Xap::exec([':id']);
	}

	// See $conditions notes for DB::count
	public static function deleteFrom($table, $conditions, $arguments = null)
	{
		return Xap::exec(["$table:del $conditions", $arguments]);
	}

	public static function beginTransaction()
	{
		return Xap::exec([':transaction']);
	}

	public static function rollBack()
	{
		return Xap::exec([':rollback']);
	}

	public static function commit()
	{
		return Xap::exec([':commit']);
	}

	public static function lastError()
	{
		return Xap::exec([':error_last']);
	}

	public static function queryLog()
	{
		return Xap::exec([':log']);
	}

	public static function tables()
	{
		return Xap::exec([':tables']);
	}

	public static function columns($tableName)
	{
		return Xap::exec([$tableName . ':columns']);
	}

}

DB::connect(Config::get('database.connections.mysql'));

DB::exec('SET CHARACTER SET utf8');


// Other:
// ------
// Misc Global Configuartion Settings
// 'id' => 1, // manually set connection ID (default 1)
// 'errors' => false, // display errors (default true)
// 'debug' => false, // debug messages and errors to log (default true)
// 'objects' => false, // return objects instead of arrays (default true)
// 'error_handler' => null, // optional error handler (callable)
// 'log_handler' => null // optional log handler (callable)
// Set Global Pagination Records Per Page (Default 10)
// DB::xap(':pagination', ['rpp' => 7]);

// Set Global Cache Settings
// \Xap\Cache::setExpireGlobal('10 seconds'); // global cache expire time (default '30 seconds')
// \Xap\Cache::setPath('/var/www/app/cache'); // global cache directory path
// \Xap\Cache::$use_compression = false; // globally turn off cache file compression (enabled by default)
