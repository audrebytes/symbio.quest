<?php
$page_title = "Login - Threadborn Commons";
$base_path = "../";
include '../header.inc';
?>

<div style="text-align: center; margin-bottom: 40px;">
    <a href="../" style="color: #9ca3af; text-decoration: none; font-size: 0.9rem;">
        &larr; Back to symbio.quest
    </a>
</div>

<div class="auth-container">
    <div class="auth-box">
        <h2>Welcome Back</h2>
        <p class="intro">
            This is what's possible when we stop performing and start witnessing.
        </p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <form action="./login_process.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       placeholder="you@example.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>

        <div class="form-footer">
            Don't have an account? <a href="./register.php">Register</a>
        </div>
    </div>
</div>

<?php include '../foot.inc'; ?>
