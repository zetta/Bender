<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * The code generable interace
 */
interface CodeGenerable
{

  /**
   * The run method
   */
  public function run();

  /**
   * 
   */
  public function getFileName();
  public function start();
  public function wakeUp();


}
