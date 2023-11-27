<style>
    /* 样式，将版权信息居中显示在白色背景上 */
    .copyright-wrapper {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: white;
        padding: 10px 0;
        text-align: center;
    }
</style>

<div class="copyright-wrapper">
    <!-- 使用PHP的date()函数获取当前年份并显示在版权信息中 -->
    <p>©
        <?php echo date("Y"); ?> 唯蟹 Team. 版权所有.
    </p>
</div>