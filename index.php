<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8"/>
    <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>صفحه اصلی</title>
    <meta content="آموزش زبان برنامه نویسی جاوااسکریپت" name="description"/>

    <!-- --------------------------------- CSS --------------------------------- -->
    <link href="./assets/css/global.css" rel="stylesheet"/>
    <link href="./assets/css/style.css" rel="stylesheet"/>
</head>

<body>
<container>
    <header>
        <?php include_once "./include/menu.php"; ?>

        <div class="headerInfo">
            <h2>جاوااسکریپتوس</h2>
            <span>آموزش جاوااسکریپت با سینا</span>
            <hr/>
            <p>
                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
                استفاده از طراحان گرافیک است
            </p>
        </div>
    </header>
    <div class="middleText">آموزش ها</div>
    <main id="mainOne">
        <div animation="fade" class="card">
            <div class="cardBody">
                <div class="cardLogo">
                    <img alt="معرفی جاوااسکریپت" src="./assets/images/1.png"/>
                </div>
                <div class="cardTitle"><h3>معرفی جاوااسکریپت</h3></div>
                <div class="cardText">
                    <h5>
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
                        استفاده از طراحان گرافیک است
                    </h5>
                </div>
                <div class="cardBtn">
                    <a href="">
                        <button>ادامه مطلب</button>
                    </a>
                </div>
            </div>

        </div>
    </main>
    <div class="middleText">بلاگ</div>
    <main id="mainTwo">
        <div animation="fade">
            <div><img alt="" src="./assets/images/blog.png" width="500px"/></div>
            <div>
                <a href="">
                    <button>برای ورود به وبلاگ کلیک کنید</button>
                </a>
            </div>
        </div>
    </main>

    <footer id="footer">
        <h1>جاوااسکریپتوس</h1>
    </footer>

    <!-- upBtn -->
    <button
            id="upBtn"
            onclick="topFunction()"
            title="برای رفتن به بالای صفحه کلیک کنید"
    >
        <img
                alt="برای رفتن به بالای صفحه کلیک کنید"
                src="./assets/images/upBtn.png"
        />
    </button>
</container>

<!-- --------------------------------- JS ---------------------------------- -->
<script src="admin/js/script.js"></script>
</body>
</html>
