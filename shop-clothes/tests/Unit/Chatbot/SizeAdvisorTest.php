<?php

namespace Tests\Unit\Chatbot;

use App\Services\Chatbot\SizeAdvisor;
use Tests\TestCase;

class SizeAdvisorTest extends TestCase
{
    public function test_demo_3_male_175cm_70kg(): void
    {
        $advice = SizeAdvisor::fromConfig()->advise('nam', 175, 70);

        $this->assertSame('L', $advice['size']);
        $this->assertSame('XL', $advice['alt_size']); // thích mặc rộng
        $this->assertFalse($advice['assumed_gender']);
    }

    public function test_demo_5_male_172cm_65kg(): void
    {
        $this->assertSame('L', SizeAdvisor::fromConfig()->advise('nam', 172, 65)['size']);
    }

    public function test_tall_and_light_bumps_one_size(): void
    {
        // Cao 1m85 (XXXL theo chiều cao) nhưng nặng 60kg (M) => lên 1 size
        $this->assertSame('L', SizeAdvisor::fromConfig()->advise('nam', 185, 60)['size']);
    }

    public function test_female_chart_and_unknown_gender(): void
    {
        $advisor = SizeAdvisor::fromConfig();

        $this->assertSame('M', $advisor->advise('nu', 158, 50)['size']);
        $this->assertTrue($advisor->advise(null, 170, 65)['assumed_gender']);
        $this->assertNull($advisor->advise('nam', null, null));
    }
}
