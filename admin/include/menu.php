<style>
    :root {
        --yellow: #f0db4f;
        --black: #323330;
    }

    .menu {
        width: 100%;
        background-color: var(--yellow);
        padding: 15px;
        display: flex;
        justify-content: center;
        place-items: center;
        gap: 5px;
        display: flex;
    }

    .menu div {
        display: flex;
        justify-content: space-around;
        place-items: center;
        flex-direction: row-reverse;
    }

    .menu a {
        transition: 300ms;
    }

    .menu a:hover {
        text-shadow: 0px 2px 5px rgba(50, 51, 48, 1);
    }

    .menu a button {
        padding: 10px 15px;
        border: none;
        background-color: var(--black);
        border-radius: 8px;
        text-align: center;
        color: #f0db4f;
        cursor: pointer;
        transition: 300ms;
    }

    .menu a button:hover {
        box-shadow: 0px 0px 20px 0px rgba(50, 51, 48, 0.5);
    }
</style>

<div class="menu" id="menu">
    <div>
        <a href="./../index.php">صفحه اصلی</a>
    </div>
</div>