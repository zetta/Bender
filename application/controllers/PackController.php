<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class PackController extends BenderController
{
  /**
   * Empaqueta un script para poder distribuirlo
   * @param string $lang
   * @param string $pattern
   */
  public function defaultAction()
  {
     $pack = new BenderPacker();
     $pack->setLang($this->lang);
     $pack->setPattern($this->pattern);
     $pack->pack();
  }

}
