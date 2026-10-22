-- =============================================================================
-- Fix "My Courses" empty for drcanham@professionalonlineeducation.com
-- Run in LIVE phpMyAdmin → SQL tab (safe to re-run).
-- =============================================================================

-- STEP 0: Check database + table health
SELECT DATABASE() AS current_database;
SELECT COUNT(*) AS total_lesson_users FROM lesson_users;
SELECT COUNT(*) AS total_lessons FROM lessons;

-- STEP 1: Check user + enrollment count
SELECT u.id, u.email, COUNT(lu.id) AS enrolled_courses
FROM users u
LEFT JOIN lesson_users lu ON lu.user_id = u.id
WHERE u.email = 'drcanham@professionalonlineeducation.com'
GROUP BY u.id, u.email;

-- STEP 2: Restore missing enrollments (lessons 38, 16, 22 — purchased Feb 07, 2023)
INSERT INTO lesson_users (lesson_id, user_id, enrolled_at, completed_at, price, status, is_show, created_at, updated_at)
SELECT 38, u.id, '2023-02-07 00:55:56', NULL, 399, 1, 0, '2023-02-07 00:55:56', '2023-02-07 00:55:56'
FROM users u
WHERE u.email = 'drcanham@professionalonlineeducation.com'
  AND NOT EXISTS (
    SELECT 1 FROM lesson_users lu WHERE lu.user_id = u.id AND lu.lesson_id = 38
  );

INSERT INTO lesson_users (lesson_id, user_id, enrolled_at, completed_at, price, status, is_show, created_at, updated_at)
SELECT 16, u.id, '2023-02-07 00:56:10', NULL, 300, 1, 0, '2023-02-07 00:56:10', '2023-02-07 00:56:10'
FROM users u
WHERE u.email = 'drcanham@professionalonlineeducation.com'
  AND NOT EXISTS (
    SELECT 1 FROM lesson_users lu WHERE lu.user_id = u.id AND lu.lesson_id = 16
  );

INSERT INTO lesson_users (lesson_id, user_id, enrolled_at, completed_at, price, status, is_show, created_at, updated_at)
SELECT 22, u.id, '2023-02-07 00:56:18', NULL, 199, 1, 0, '2023-02-07 00:56:18', '2023-02-07 00:56:18'
FROM users u
WHERE u.email = 'drcanham@professionalonlineeducation.com'
  AND NOT EXISTS (
    SELECT 1 FROM lesson_users lu WHERE lu.user_id = u.id AND lu.lesson_id = 22
  );

-- STEP 3: Verify after fix (should show 3 rows)
SELECT u.id, u.email, lu.lesson_id, l.title, lu.enrolled_at
FROM users u
JOIN lesson_users lu ON lu.user_id = u.id
LEFT JOIN lessons l ON l.id = lu.lesson_id
WHERE u.email = 'drcanham@professionalonlineeducation.com'
ORDER BY lu.enrolled_at;
