Middleware is a [callable](http://php.net/manual/en/language.types.callable.php) that interacts with the request-response cycle. 
It may modify the request/response, terminate the request-response cycle early, and call the next middleware in the stack. 
Middleware accepts the following arguments:

  * ```$request``` **MUST** implement `Psr\HttpMessage\RequestInterface`
  * ```$response``` **MUST** implement \Psr\HttpMessage\ResponseInterface`
  * ```$next``` **MUST** be a callable
   
Middleware may be loaded at the application level or router level and may include a mount path. Loading a series of 
middleware on the same mount path will create a stack for that path. 

Application Level Middleware
----------------------------



Router Level Middleware
-----------------------
