<?php

namespace App\Helpers;

class LeadHealth
{
    public static function calculateScore(array $lead): array
    {
        $lastContact = $lead['last_follow_up'] ? strtotime($lead['last_follow_up']) : null;
        $nextContact = $lead['next_follow_up'] ? strtotime($lead['next_follow_up']) : null;
        $status = $lead['status'];

        // Default to Healthy if brand new and uncontacted
        if ($status === 'New' && !$lastContact && !$nextContact) {
            return ['score' => 80, 'label' => 'Healthy', 'color' => 'bg-green-100 text-green-800'];
        }

        // If converted or lost, health isn't primarily derived from follow-ups
        if ($status === 'Converted' || $status === 'Lost') {
            return ['score' => 100, 'label' => 'Inactive (Closed)', 'color' => 'bg-gray-100 text-gray-800'];
        }

        $now = time();
        $daysSinceLastContact = $lastContact ? floor(($now - $lastContact) / 86400) : 999;

        $score = 100;

        // Deduct points for aging without contact
        if ($daysSinceLastContact > 30) $score -= 40;
        elseif ($daysSinceLastContact > 14) $score -= 20;
        elseif ($daysSinceLastContact > 7) $score -= 10;

        // Deduct points for missing next steps
        if (!$nextContact) {
            $score -= 30; // No future plan!
        } else {
            // If overdue, huge deduction
            if ($nextContact < $now) {
                $score -= 50;
            }
        }

        $score = max(0, min(100, $score));

        if ($score >= 80) {
            return ['score' => $score, 'label' => 'Healthy', 'color' => 'bg-green-100 text-green-800'];
        } elseif ($score >= 50) {
            return ['score' => $score, 'label' => 'Attention Needed', 'color' => 'bg-yellow-100 text-yellow-800'];
        } elseif ($score >= 30) {
            return ['score' => $score, 'label' => 'At Risk', 'color' => 'bg-orange-100 text-orange-800'];
        } else {
            return ['score' => $score, 'label' => 'Overdue / No Follow-Up', 'color' => 'bg-red-100 text-red-800'];
        }
    }
}
