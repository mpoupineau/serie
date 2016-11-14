<?php

namespace Serie\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SerieUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
