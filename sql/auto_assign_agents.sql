-- Auto-assign delivery agents to orders based on customer state
-- This script will assign the first active agent found for each state

UPDATE orders o
INNER JOIN agent_states ast ON o.state = ast.state
INNER JOIN delivery_agents da ON ast.agent_id = da.id
SET o.agent_id = da.id
WHERE o.agent_id IS NULL 
AND da.status = 'active';

-- Verify assignments
SELECT 
    o.id,
    o.fullname,
    o.state,
    o.pack,
    o.status,
    o.agent_id,
    da.name as agent_name,
    o.created_at
FROM orders o
LEFT JOIN delivery_agents da ON o.agent_id = da.id
ORDER BY o.created_at DESC;

-- Show orders without agents (states with no agent coverage)
SELECT 
    o.id,
    o.fullname,
    o.state,
    o.pack,
    'No agent assigned - state not covered' as reason
FROM orders o
WHERE o.agent_id IS NULL;

-- Show agent workload
SELECT 
    da.name as agent_name,
    da.status,
    COUNT(o.id) as total_orders,
    SUM(CASE WHEN o.status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
    SUM(CASE WHEN o.status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_orders,
    SUM(CASE WHEN o.status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
    GROUP_CONCAT(DISTINCT ast.state ORDER BY ast.state) as states_covered
FROM delivery_agents da
LEFT JOIN orders o ON da.id = o.agent_id
LEFT JOIN agent_states ast ON da.id = ast.agent_id
GROUP BY da.id, da.name, da.status
ORDER BY total_orders DESC;
