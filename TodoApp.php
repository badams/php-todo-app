<?php
/**
 * A simple object oriented PHP Todo-List application
 *
 * @author Byron Adams <byron.adams54@gmail.com>
 * @copyright 2013 Byron Adams
 */
class TodoApp {
	
	/**
	 * Array used to store error messages, which are display to the user.
	 */
	private $errors = array();
	
	/**
	 *  
	 */
	const SQL_CREATE_TABLE = '
		CREATE TABLE IF NOT EXISTS todos (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			title VARCHAR(256) NOT NULL, 
			done BOOLEAN NOT NULL DEFAULT FALSE
		);';

	/**
	 * This is the contructor for our application, it initalizes the connection
	 * with the database server, and runs our schema if needed.
	 * 
	 * @param array $DB_URL the result of calling parse_url on a valid mysql url.
	 */
	function __construct($DB_URL) {
	
		// we need to alter the path slightly to extract a valid database name
		$db_name = substr($DB_URL['path'], 1);
		
		// connect to the database, given the details provided in $DB_URL
		$this->db = new mysqli($DB_URL['host'], $DB_URL['user'], $DB_URL['pass'], $db_name);
		
		// If the connection failed, die.
		if ($this->db->connect_errno) {
			die($this->db->connect_error);
		}
		
		// attempt to run the schema for the `todos` table.
		if (FALSE == $this->db->query(self::SQL_CREATE_TABLE)) {
			die($this->db->connect_error);
		}
	}	
	/**
	 * Inserts a new todo item into the `todos` table with the 
	 * given $title, the $title must not be empty and can only contain 
	 * alphanumeric characters, spaces, and the following "special" 
	 * characters .,!@?#&
	 * 
	 * @param string $title
	 * @return void
	 */
	public function add_item($title) {
		
		$title = trim($title);
		
		if ('' !== $title && preg_match('#^[.,!@\?\#\&A-Z0-9 ]+$#i', $title)) {
			$stmt = $this->db->prepare('INSERT INTO todos VALUES("", ?, "")');
			$stmt->bind_param('s', $title);
			$stmt->execute();
			$this->redirect();
		} else {
			$this->error('Invalid Title! <strong>,!@?#&</strong> are the only special characters aloud.');
		}
	}	
	/**
	 *  Marks the given todo item as completed, If the $id parameter is 
	 *  invalid nothing will happen. After this function has executed 
	 *  the user will be redirected to the main page.
	 *  
	 * @param int $id 
	 * @return void
	 */
	public function finish_item($id) {

		if ($stmt = $this->db->prepare('UPDATE todos SET done=TRUE WHERE id=?')) {
			$id = (int)$id;
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->close();
		}
		$this->redirect();
	}	
	/**
	 * Deletes the given todo item, if the $id parameter is invalid 
	 * nothing will happen. The users page will be refreshed after 
	 * this has been executed.
	 * 
	 * @param int $id
	 * @return void
	 */
	public function delete_item($id) {
		
		if ($stmt = $this->db->prepare('DELETE FROM todos WHERE id=?')) {
			$id = (int)$id;
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->close();
		}
		$this->redirect();
	}
	/**
	 *  Fetch all items in the todo table ordered by id (desc)
	 *  if no items found, an empty array is returned.
	 * 
	 *  @return array $items
	 */
	public function get_items() {
		
		$items = array();
		$result = $this->db->query('SELECT * FROM todos ORDER BY id DESC');		
		
		while ($row = $result->fetch_assoc()) array_push($items, $row);
		
		$result->free();		
		return $items;
	}
	/**
	 *  Renders the template file in the context of this TodoApp instance. 
	 */
	public function render() {
		require 'template.php';
	}
	/**
	 * Simple redirect function.
	 * @param string $url optional
	 */
	public function redirect($url='') {
		header('Location: '.$url);
	}	
	/**
	 * Appends a new error message to the errors list.
	 * @param string $message 
	 */	
	private function error($message) {
		array_push($this->errors, $message);
	}

	function __destruct() {
		$this->db->close();
	}
}
?>
