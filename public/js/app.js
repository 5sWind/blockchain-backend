// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app  = new Framework7({
  root: '#app', // App root element
  id: 'io.framework7.testapp', // App bundle ID
  name: 'Framework7', // App name
  theme: 'auto', // Automatic theme detection
  // App root data
  data: function () {
    return {
      user: {
        firstName: 'John',
        lastName: 'Doe',
      },
    };
  },
  // App root methods
  methods: {
    helloWorld: function () {
      app.dialog.alert('Hello World!');
    },
  },
  // App routes
  routes: routes,
  // Enable panel left visibility breakpoint
  panel: {
    leftBreakpoint: 960,
  },
});

// Init/Create left panel view
var mainView = app.views.create('.view-left', {
  url: '/'
});

// Init/Create main view
var mainView = app.views.create('.view-main', {
  url: '/'
});

// Login Screen Demo
$$('#my-login-screen .login-button').on('click', function () {
  var username = $$('#my-login-screen [name="username"]').val();

  // Close login screen
  // app.loginScreen.close('#my-login-screen');

    app.request({
        url: '/api/signup',
        method: 'POST',
        dataType: 'json',
        //send "query" to server. Useful in case you generate response dynamically
        data: {
            fingerprint: client.getFingerprint(),
            username: username
        },
        success: function (data) {
            if (data.success === false) {
                toastr.error(data.msg, "错误")
            } else {
                window.location.reload();
            }
        }
    });

});

var client = new ClientJS();

app.request({
    url: '/api/login',
    method: 'POST',
    dataType: 'json',
    //send "query" to server. Useful in case you generate response dynamically
    data: {
        fingerprint: client.getFingerprint(),
    },
    success: function (data) {
        if (data.success === false) {
            var login_screen = app.loginScreen.create({
                el: $$("#my-login-screen"),
            });
            login_screen.open();
        }
    }
});

app.on('pageInit', function (page) {
    if (page.name === 'user') {
        app.preloader.hide();
    } else if (page.name === 'upload') {
        $$("#upload_btn").on('click', function () {
            var data = $$("#msg").val();

            var reader = new FileReader();
            reader.readAsDataURL(document.querySelector('#picture').files[0]);
            reader.onload = function () {
                var filedata = reader.result.split(",")[1];
                app.request({
                    url: '/api/upload',
                    method: 'POST',
                    dataType: 'json',
                    //send "query" to server. Useful in case you generate response dynamically
                    data: {
                        filedata: filedata,
                        data: data
                    },
                    success: function (data) {
                        if (data.success === false) {
                            toastr.error(data.msg, "错误")
                        } else {
                            toastr.success("上传成功", "成功")
                        }
                    }
                });
            };
            reader.onerror = function (error) {
                console.log('Error: ', error);
            };


        });
    }
});