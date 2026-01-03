<?php
header("Content-Type: application/json");
include 'db.php';

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Users API",
 *         version="1.0.0",
 *         description="Simple CRUD API for Users table"
 *     ),
 *     @OA\Components(
 *         @OA\Schema(
 *             schema="User",
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Anand"),
 *             @OA\Property(property="email", type="string", example="anand@example.com")
 *         )
 *     )
 * )
 */

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;

    case 'POST':
        handlePost($pdo, $input);
        break;

    case 'PUT':
        handlePut($pdo, $input);
        break;

    case 'DELETE':
        handleDelete($pdo, $input);
        break;

    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

/**
 * @OA\Get(
 *     path="/api.php",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="List of users",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         )
 *     )
 * )
 */
function handleGet($pdo) {
    $sql = "SELECT * FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

/**
 * @OA\Post(
 *     path="/api.php",
 *     summary="Create a new user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email"},
 *             @OA\Property(property="name", type="string", example="Anand"),
 *             @OA\Property(property="email", type="string", example="anand@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created"
 *     )
 * )
 */
function handlePost($pdo, $input) {
    $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $input['name'],
        'email' => $input['email']
    ]);
    echo json_encode(['message' => 'User created successfully']);
}

/**
 * @OA\Put(
 *     path="/api.php",
 *     summary="Update existing user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "name", "email"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="New Name"),
 *             @OA\Property(property="email", type="string", example="new@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated"
 *     )
 * )
 */
function handlePut($pdo, $input) {
    $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $input['name'],
        'email' => $input['email'],
        'id' => $input['id']
    ]);
    echo json_encode(['message' => 'User updated successfully']);
}

/**
 * @OA\Delete(
 *     path="/api.php",
 *     summary="Delete user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id"},
 *             @OA\Property(property="id", type="integer",example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted"
 *     )
 * )
 */
function handleDelete($pdo, $input) {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $input['id']]);
    echo json_encode(['message' => 'User deleted successfully']);
}
