<?php
// api/agents.php - Delivery Agents Management API
require_once '../php/auth.php';
requireLogin();
require_once '../php/db.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            if (isset($_GET['action'])) {
                switch($_GET['action']) {
                    case 'list':
                        listAgents();
                        break;
                    case 'single':
                        getSingleAgent();
                        break;
                    case 'states':
                        getAgentStatesAPI();
                        break;
                    default:
                        listAgents();
                }
            } else {
                listAgents();
            }
            break;
            
        case 'POST':
            createAgent();
            break;
            
        case 'PUT':
            updateAgent();
            break;
            
        case 'DELETE':
            deleteAgent();
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function listAgents() {
    global $conn;
    
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
    
    // Build WHERE clause
    $where = [];
    if (!empty($search)) {
        $where[] = "(da.name LIKE '%$search%' OR da.email LIKE '%$search%' OR da.phone LIKE '%$search%')";
    }
    if (!empty($status)) {
        $where[] = "da.status = '$status'";
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Get agents with their states
    $sql = "SELECT da.*, 
                GROUP_CONCAT(DISTINCT as2.state ORDER BY as2.state SEPARATOR ', ') as states,
                COUNT(DISTINCT as2.state) as state_count,
                COUNT(DISTINCT o.id) as total_orders
            FROM delivery_agents da
            LEFT JOIN agent_states as2 ON da.id = as2.agent_id
            LEFT JOIN orders o ON da.id = o.agent_id
            $whereClause
            GROUP BY da.id
            ORDER BY da.created_at DESC";
    
    $result = $conn->query($sql);
    $agents = [];
    
    while ($row = $result->fetch_assoc()) {
        $agents[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $agents]);
}

function getSingleAgent() {
    global $conn;
    
    if (!isset($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'Agent ID required']);
        return;
    }
    
    $agentId = (int)$_GET['id'];
    
    // Get agent details
    $sql = "SELECT * FROM delivery_agents WHERE id = $agentId";
    $result = $conn->query($sql);
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Agent not found']);
        return;
    }
    
    $agent = $result->fetch_assoc();
    
    // Get agent's states
    $statesSql = "SELECT state FROM agent_states WHERE agent_id = $agentId ORDER BY state";
    $statesResult = $conn->query($statesSql);
    $states = [];
    
    while ($row = $statesResult->fetch_assoc()) {
        $states[] = $row['state'];
    }
    
    $agent['states'] = $states;
    
    // Get stock summary for this agent's states
    $stockSql = "SELECT si.state, si.package_type, si.quantity
                FROM stock_inventory si
                INNER JOIN agent_states as2 ON si.state = as2.state
                WHERE as2.agent_id = $agentId
                ORDER BY si.state, si.package_type";
    
    $stockResult = $conn->query($stockSql);
    $stock = [];
    
    while ($row = $stockResult->fetch_assoc()) {
        if (!isset($stock[$row['state']])) {
            $stock[$row['state']] = [];
        }
        $stock[$row['state']][$row['package_type']] = $row['quantity'];
    }
    
    $agent['stock_by_state'] = $stock;
    
    echo json_encode(['success' => true, 'data' => $agent]);
}

function getAgentStatesAPI() {
    global $conn;
    
    if (!isset($_GET['agent_id'])) {
        echo json_encode(['success' => false, 'message' => 'Agent ID required']);
        return;
    }
    
    $agentId = (int)$_GET['agent_id'];
    
    $sql = "SELECT state FROM agent_states WHERE agent_id = $agentId ORDER BY state";
    $result = $conn->query($sql);
    $states = [];
    
    while ($row = $result->fetch_assoc()) {
        $states[] = $row['state'];
    }
    
    echo json_encode(['success' => true, 'data' => $states]);
}

function createAgent() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['name']) || !isset($input['phone'])) {
        echo json_encode(['success' => false, 'message' => 'Name and phone are required']);
        return;
    }
    
    $name = $conn->real_escape_string($input['name']);
    $email = isset($input['email']) ? $conn->real_escape_string($input['email']) : '';
    $phone = $conn->real_escape_string($input['phone']);
    $altPhone = isset($input['alt_phone']) ? $conn->real_escape_string($input['alt_phone']) : '';
    $address = isset($input['address']) ? $conn->real_escape_string($input['address']) : '';
    $status = isset($input['status']) ? $conn->real_escape_string($input['status']) : 'active';
    $states = isset($input['states']) ? $input['states'] : [];
    
    // Insert agent
    $sql = "INSERT INTO delivery_agents (name, email, phone, alt_phone, address, status)
            VALUES ('$name', '$email', '$phone', '$altPhone', '$address', '$status')";
    
    if ($conn->query($sql)) {
        $agentId = $conn->insert_id;
        
        // Insert agent states
        if (!empty($states)) {
            foreach ($states as $state) {
                $state = $conn->real_escape_string($state);
                $stateSql = "INSERT INTO agent_states (agent_id, state) VALUES ($agentId, '$state')";
                $conn->query($stateSql);
                
                // Update stock_inventory to link this agent
                $updateStockSql = "UPDATE stock_inventory SET agent_id = $agentId WHERE state = '$state'";
                $conn->query($updateStockSql);
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Agent created successfully', 'agent_id' => $agentId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create agent: ' . $conn->error]);
    }
}

function updateAgent() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id'])) {
        echo json_encode(['success' => false, 'message' => 'Agent ID required']);
        return;
    }
    
    $agentId = (int)$input['id'];
    $updateParts = [];
    
    if (isset($input['name'])) {
        $name = $conn->real_escape_string($input['name']);
        $updateParts[] = "name = '$name'";
    }
    
    if (isset($input['email'])) {
        $email = $conn->real_escape_string($input['email']);
        $updateParts[] = "email = '$email'";
    }
    
    if (isset($input['phone'])) {
        $phone = $conn->real_escape_string($input['phone']);
        $updateParts[] = "phone = '$phone'";
    }
    
    if (isset($input['alt_phone'])) {
        $altPhone = $conn->real_escape_string($input['alt_phone']);
        $updateParts[] = "alt_phone = '$altPhone'";
    }
    
    if (isset($input['address'])) {
        $address = $conn->real_escape_string($input['address']);
        $updateParts[] = "address = '$address'";
    }
    
    if (isset($input['status'])) {
        $status = $conn->real_escape_string($input['status']);
        $updateParts[] = "status = '$status'";
    }
    
    if (!empty($updateParts)) {
        $sql = "UPDATE delivery_agents SET " . implode(', ', $updateParts) . " WHERE id = $agentId";
        $conn->query($sql);
    }
    
    // Update states if provided
    if (isset($input['states'])) {
        // Delete old states
        $conn->query("DELETE FROM agent_states WHERE agent_id = $agentId");
        
        // Clear agent_id from old stock inventory
        $conn->query("UPDATE stock_inventory SET agent_id = NULL WHERE agent_id = $agentId");
        
        // Insert new states
        foreach ($input['states'] as $state) {
            $state = $conn->real_escape_string($state);
            $stateSql = "INSERT INTO agent_states (agent_id, state) VALUES ($agentId, '$state')";
            $conn->query($stateSql);
            
            // Update stock inventory
            $updateStockSql = "UPDATE stock_inventory SET agent_id = $agentId WHERE state = '$state'";
            $conn->query($updateStockSql);
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Agent updated successfully']);
}

function deleteAgent() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id'])) {
        echo json_encode(['success' => false, 'message' => 'Agent ID required']);
        return;
    }
    
    $agentId = (int)$input['id'];
    
    // Check if agent has orders
    $checkSql = "SELECT COUNT(*) as order_count FROM orders WHERE agent_id = $agentId";
    $result = $conn->query($checkSql);
    $row = $result->fetch_assoc();
    
    if ($row['order_count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete agent with existing orders. Set status to inactive instead.']);
        return;
    }
    
    // Clear agent from stock inventory
    $conn->query("UPDATE stock_inventory SET agent_id = NULL WHERE agent_id = $agentId");
    
    // Delete agent (agent_states will be deleted via CASCADE)
    $sql = "DELETE FROM delivery_agents WHERE id = $agentId";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Agent deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete agent: ' . $conn->error]);
    }
}
?>
