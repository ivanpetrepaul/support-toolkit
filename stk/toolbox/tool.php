<?php

class stk_toolbox_tool
{
	private $active;
	private $tool;

	/**
	 * Initialise tool object
	 *
	 * This is a wrapper object that contains a tool for the support toolkit,
	 * this method should be called when initialising a new tool. It handles
	 * some initial validation on the tool and returns the `stk_toolbox_tool`
	 * object for the requested tool
	 *
	 * @param SplFileInfo $path Path to the tool file, the correct class name is
	 *                          determined from here
	 * @return string|\static The `stk_toolbox_tool` object or a string when an
	 *                        error occured.
	 */
	static public function createTool(SplFileInfo $path)
	{
		// Determine the class name
		$category	= substr(strrchr($path->getPath(), '/'), 1);
		$file		= $path->getBasename('.php');
		$className	= "stktool_{$category}_{$file}";

		// Test whether the class name is correctly formatted
		if (!preg_match('#^stktool_[a-zA-Z]+_[a-zA-Z_]+$#', $className))
		{
			return 'TOOL_CLASSNAME_WRONG_FORMAT';
		}

		$rc = new ReflectionClass($className);

		// Must implement the interface
		if (false === ($rc->implementsInterface('stk_toolbox_toolInterface')))
		{
			return 'TOOL_CLASS_NOT_IMPLEMENTS_INTERFACE';
		}

		return new static($rc->newInstanceArgs(), $category, $file);
	}

	final private function __construct(stk_toolbox_toolInterface $tool, $categoryName = '', $toolName = '')
	{
		$this->active	= false;
		$this->category	= $categoryName;
		$this->id		= $toolName;
		$this->tool		= $tool;
	}

	public function isActive()
	{
		return $this->active;
	}

	public function setActive($active = false)
	{
		$this->active = $active;
	}

	public function getID()
	{
		return $this->id;
	}

	public function getTool()
	{
		return $this->tool;
	}

	public function getToolURL(array $params = array())
	{
		// Add cat/tool to the params
		$params['c'] = $this->category;
		$params['t'] = $this->id;

		return append_sid(STK_WEB_PATH . '/index.php', $params);
	}
}