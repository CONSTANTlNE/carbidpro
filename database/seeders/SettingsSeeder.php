<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terms=Setting::create([
            'label'=>'Terms and Conditions',
            'key'=>'terms',
            'value'=>"<p>        </p><p>        </p><p>        </p><p>Terms and Conditions</p><p><br></p><p>These terms and conditions (\"Terms\", \"Agreement\") are an agreement between you (\"User\", \"You\") and Carbidpro (\"Carbidpro\", \"We\", \"Us\", \"Our\") governing your use of Carbidpro.com. This Agreement sets forth the general terms and conditions of your use of the Website and any of its products or services (collectively, \"Services\").</p><p><br></p><p>Accounts and Membership</p><p><br></p><p>If you create an account on the Website, you are responsible for maintaining the security of your account and for any activities or actions occurring under your account. You agree to notify us immediately of any unauthorized access to or use of your account.</p><p><br></p><p>Intellectual Property</p><p><br></p><p>The Website and its original content, features, and functionality are owned by [Your Company] and are protected by international copyright, trademark, patent, trade secret, and other intellectual property or proprietary rights laws.</p><p><br></p><p>User Content</p><p><br></p><p>You retain ownership of any content you post on the Website (\"User Content\"). However, by posting User Content, you grant us a non-exclusive, worldwide, royalty-free, perpetual, irrevocable, sublicensable license to use, reproduce, adapt, publish, translate, and distribute it in any and all media.</p><p><br></p><p>Limitation of Liability</p><p><br></p><p>To the fullest extent permitted by applicable law, in no event will [Your Company] be liable to any person for any indirect, incidental, special, punitive, cover or consequential damages arising out of or in connection with your use of the Website, whether or not [Your Company] has been advised of the possibility of such damages.</p><p><br></p><p>Indemnification</p><p><br></p><p>You agree to indemnify and hold harmless [Your Company] and its affiliates, officers, directors, employees, agents, and licensors from and against any and all losses, expenses, damages, liabilities, and costs, including attorneys' fees, arising out of or related to your use of the Website or any violation of these Terms.</p><p><br></p><p>Termination</p><p><br></p><p>We may terminate or suspend your access to the Website immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach these Terms.</p><p><br></p><p>Governing Law</p><p><br></p><p>This Agreement is governed by and construed in accordance with the laws of [Your Country], without regard to its conflict of law principles.</p><p><br></p><p>Changes to This Agreement</p><p><br></p><p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. By continuing to access or use our Website after those revisions become effective, you agree to be bound by the revised terms.</p><p><br></p><p>Contact Information</p><p><br></p><p>If you have any questions about these Terms, please contact us at info@carbidpro.com.</p><p>By using the Website, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.</p><p>\r\n    </p><p>\r\n    </p><p>\r\n    </p>"
        ]);
    }
}
