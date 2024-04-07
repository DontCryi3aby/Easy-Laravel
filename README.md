## Easy Laravel Globo Tutorial

## Author: Nguyen Ngoc Thach

## ROADMAP

### Week 1 : Introduction to PHP & Database (MySQL)

### Week 2 : Introduction to Laravel & Laravel Database Migrations

### Week 3 : Laravel Basics

-   [Routing](https://laravel.com/docs/10.x/routing).
-   [Middleware](https://laravel.com/docs/10.x/middleware).
-   [Controllers](https://laravel.com/docs/10.x/controllers).
-   [Requests](https://laravel.com/docs/10.x/requests).
-   [Responses](https://laravel.com/docs/10.x/responses).
-   [Collections](https://laravel.com/docs/10.x/collections).
-   [Mail](https://laravel.com/docs/10.x/mail).

    ### Routing
    - Tất cả các route (tuyến đường) được định nghĩa trong các files trong thư mục routes
    - Một laravel route cơ bản gồm một URI và một closure
        Route::get('/greeting', function () {
            return 'Hello World';
        });
    - Router Methods:
        Route::get($uri, $callback);
        Route::post($uri, $callback);
        Route::put($uri, $callback);
        Route::patch($uri, $callback);
        Route::delete($uri, $callback);
        Route::options($uri, $callback);
    - CSRF Protection: Các route POST, PUT, PATCH, DELETE trong file web.php phải bao gồm CSRF token
    - Redirect Routes:
        Route::redirect('/here', '/there');
    - View Routes: Nếu route chỉ cần trả về view thì có thể sử dụng Route::view:
        Route::view('/welcome', 'welcome');
    - The Route List: Để xem tất cả các route được định nghĩa:
        php artisan route:list [options]
    - Route Parameters:
        Required Parameters:
            Route::get('/user/{id}', function (string $id) {
                return 'User '.$id;
            });
        Optional Parameters:
            Route::get('/user/{name?}', function (?string $name = null) {
                return $name;
            });
    - Named Routes:
        Define:
            Route::get('/user/profile', function () {
                // ...
            })->name('profile');
        Sử dụng named routes:
            $url = route('profile');
            return redirect()->route('profile');
    - Route Groups:
        Middleware:
            Route::middleware(['first', 'second'])->group(function () {
                Route::get('/', function () {
                // Uses first & second middleware...
                });
                Route::get('/user/profile', function () {
                // Uses first & second middleware...
                });
            });
        Controllers:
            Route::controller(OrderController::class)->group(function () {
                Route::get('/orders/{id}', 'show');
                Route::post('/orders', 'store');
            });
        Route Prefixes:
            Route::prefix('admin')->group(function () {
                Route::get('/users', function () {
                // Matches The "/admin/users" URL
                });
            });
    - Route Model Binding:
        Implicit Binding:
            Route::get('/users/{user}', function (User $user) {
                return $user->email;
            });
        Customizing the Key
            Route::get('/posts/{post:slug}', function (Post $post) {
                return $post;
            });

    ### Controllers
    - Location: app/Http/Controllers
    - Basic Controllers:
        php artisan make:controller UserController
    - Example Basic Controllers:
        // UserController.php
        class UserController extends Controller
        {
            public function show(string $id): View
            {
                return view('user.profile', [
                    'user' => User::findOrFail($id)
                ]);
            }
        }
        // routes/web.php
        Route::get('/user/{id}', [UserController::class, 'show']);
    - Single Action Controllers:
        Example:
            class ProvisionServer extends Controller
            {
                public function __invoke()
                {
                    // ...
                }
            }
            Route::post('/server', ProvisionServer::class);
        Cách tạo:
            php artisan make:controller ProvisionServer --invokable
    - Resource Controllers (Important):
        Create a resoure controller:
            php artisan make:controller PhotoController --resource
        Register a resource route:
            Route::resource('photos', PhotoController::class);
    - Partial Resource Routes:
        Example:
            Route::resource('photos', PhotoController::class)->only([
                'index', 'show'
            ]);
            
            Route::resource('photos', PhotoController::class)->except([
                'create', 'store', 'update', 'destroy'
            ]);
    - Nested Resources:
        Example:
            Route::resource('photos.comments', PhotoCommentController::class);
            => URL: /photos/{photo}/comments/{comment}
        Shallow Nesting:
            Route::resource('photos.comments', CommentController::class)->shallow();
    - Singleton Resource Controllers:
        Example:
            Route::singleton('profile', ProfileController::class);
    
    ### Requests
    - Accessing the Request:
        Request $request
    - Request Path, Host, and Method:
        Request Path: $request->path();
        Request URL: $request->url();
        Request Host:
            $request->host();
            $request->httpHost();
        Request Method: $request->method();
    - Request Headers:
        Example:
            - $value = $request->header('X-Header-Name');
            - if ($request->hasHeader('X-Header-Name')) {
                // ...
            }
            - $token = $request->bearerToken();
    - Input (Important):
        Retrieving All Input Data:
            $input = $request->all();
            $input = $request->collect();
            $input = $request->input();
        Retrieving an Input Value:
            Example:
                $name = $request->input('name');
                $name = $request->input('products.0.name');
                $names = $request->input('products.*.name');
        Retrieving Input From the Query String:
            $name = $request->query('name');
        Input Presence:
            Methods: has(), hasAny(), whenHas(), filled(), anyFilled(), whenFilled(), missing(), whenMissing(), ...
    - Cookies:
        Retrieving Cookies From Requests
            $value = $request->cookie('name');
    - Files:
        Retrieving Uploaded Files:
            $file = $request->file('photo');
            or
            $file = $request->photo;
        File Paths and Extensions:
            $path = $request->photo->path();
            $extension = $request->photo->extension();
        Storing Uploaded Files:
            $path = $request->photo->store('images');
            $path = $request->photo->store('images', 's3');

    ### Responses
    - Response Objects: response()
    - Attaching Headers to Responses:
        return response($content)
            ->withHeaders([
                'Content-Type' => $type,
                'X-Header-One' => 'Header Value',
                'X-Header-Two' => 'Header Value',
            ]);
    - Attaching Cookies to Responses:
        return response('Hello World')->cookie(
            'name', 'value', $minutes
        );
    - Redirects: redirect()
    - Response Types:
        View Responses:
            return response()
            ->view('hello', $data, 200)
            ->header('Content-Type', $type);
        JSON Responses:
            return response()->json([
                'name' => 'Abigail',
                'state' => 'CA',
            ]);
        File Downloads:
            return response()->download($pathToFile);
        File Responses:
            return response()->file($pathToFile);

    ### Middleware
    - Middleware cho phép kiểm tra và lọc các yêu cầu HTTP
        Ví dụ: Middleware xác thực người dùng, nếu người dùng chưa được xác thực, middleware chuyển hướng
            người dùng đến trang đăng nhập, còn nếu người dùng được xác thực, cho phép người dùng can thiệp
            sâu hơn vào ứng dụng
    - Defining Middleware:
        php artisan make:middleware EnsureTokenIsValid
    - Example:
        class EnsureTokenIsValid
        {
            public function handle(Request $request, Closure $next): Response
            {
                if ($request->input('token') !== 'my-secret-token') {
                    return redirect('home');
                }
        
                return $next($request);
            }
        }
    - Registering Middleware:
        Global Middleware: tạo property của đối tượng $middleware trong file app/Http/Kernel.php
        Assigning Middleware to Routes:
            Route::get('/', function () {
                // ...
            })->middleware([First::class, Second::class]);
        Excluding Middleware:
            Route::middleware([EnsureTokenIsValid::class])->group(function () {
                Route::get('/', function () {
                    // ...
                });
                Route::get('/profile', function () {
                    // ...
                })->withoutMiddleware([EnsureTokenIsValid::class]);
            });

    ### Implemented with the project NOTE
    - Resource Controllers:
        - Register Resource Route:
            Route::resource('posts', PostController::class);
        - Shallow Nesting
            Route::resource('posts.comments', PostCommentController::class)->shallow();
        -> Lý do sử dụng: Có sẵn những khai báo cho các hành động CRUD cũng như tự động xác định URI cần thiết để xử lý các yêu cầu CRUD tương ứng. Các routes được tạo cũng được đặt tên sẵn.
    - Middleware:
        - Authentication: Khi người dùng xác thực thành công, thì mới cho người dùng đi sâu vào những chức năng của ứng dụng
