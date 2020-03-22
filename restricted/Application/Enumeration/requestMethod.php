<?php

namespace Application\Enumeration;

class requestMethod extends Base
{
    const PUT = 0x01;
    const POST = 0x02;
    const GET = 0x04;
    const HEAD = 0x08;
    const DELETE = 0x10;
    const OPTIONS = 0x20;
}


?>