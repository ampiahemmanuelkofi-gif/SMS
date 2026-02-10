<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter - <?php echo Security::clean($application['first_name'] . ' ' . $application['last_name']); ?></title>
    <style>
        body { font-family: 'Times New Roman', serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 40px; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        .school-info { font-size: 14px; }
        .recipient { margin-bottom: 30px; }
        .content { margin-bottom: 50px; text-align: justify; }
        .signature { margin-top: 60px; }
        .footer { font-size: 12px; text-align: center; border-top: 1px solid #ccc; padding-top: 20px; color: #666; }
        .btn-print { display: block; margin: 20px auto; padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; text-align: center; border-radius: 5px; width: 100px; font-family: sans-serif; }
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>
    <a href="#" onclick="window.print(); return false;" class="btn-print">Print</a>

    <div class="header">
        <div class="logo"><?php echo SCHOOL_NAME; ?></div>
        <div class="school-info">
            123 Education Lane, Knowledge City<br>
            Phone: +233 123 456 789 | Email: admissions@school.edu.gh
        </div>
    </div>

    <div class="recipient">
        <strong>Date:</strong> <?php echo date('F d, Y'); ?><br><br>
        <strong>To:</strong><br>
        <?php echo Security::clean($application['guardian_name']); ?><br>
        Parent/Guardian of <?php echo Security::clean($application['first_name'] . ' ' . $application['last_name']); ?><br>
        <?php echo nl2br(Security::clean($application['address'])); ?>
    </div>

    <div class="content">
        <h3>OFFICIAL OFFER OF ADMISSION</h3>
        
        <p>Dear <?php echo Security::clean($application['guardian_name']); ?>,</p>
        
        <p>We are pleased to inform you that your ward, <strong><?php echo Security::clean($application['first_name'] . ' ' . $application['last_name']); ?></strong>, has been offered admission into <strong><?php echo Security::clean($application['class_name']); ?></strong> at <?php echo SCHOOL_NAME; ?> for the <?php echo date('Y'); ?>/<?php echo date('Y') + 1; ?> academic year.</p>
        
        <p>This offer is based on the successful comprehensive assessment and interview performance. We are confident that <?php echo Security::clean($application['first_name']); ?> will thrive in our academic environment.</p>
        
        <p><strong>Next Steps:</strong></p>
        <ol>
            <li>Please accept this offer by paying the non-refundable admission fee.</li>
            <li>Submit all required documentation verified during the interview.</li>
            <li>Complete the medical and emergency contact forms.</li>
        </ol>
        
        <p>Please note that this offer is valid for 14 days from the date of this letter. Failure to accept the offer within this period may result in the forfeiture of the admission slot to a student on our waiting list.</p>
        
        <p>We look forward to welcoming your family to our school community.</p>
    </div>

    <div class="signature">
        <p>Sincerely,</p>
        <br><br>
        __________________________<br>
        <strong>Principal / Head of Admissions</strong><br>
        <?php echo SCHOOL_NAME; ?>
    </div>

    <div class="footer">
        <?php echo SCHOOL_MOTTO; ?>
    </div>
</body>
</html>
