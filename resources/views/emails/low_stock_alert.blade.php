<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Low Stock Alert</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; padding: 30px;">

    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
        <tr>
            <td style="background: #d32f2f; color: #ffffff; padding: 20px; text-align: center; font-size: 20px; font-weight: bold; border-radius: 8px 8px 0 0;">
                ⚠️ Low Stock Alert
            </td>
        </tr>
        <tr>
            <td style="padding: 30px; color: #333333; line-height: 1.6; font-size: 16px;">
                <p>Hello Mohamed Hamed,</p>
                <p>The following product is running low on stock:</p>

                <table width="100%" cellpadding="10" cellspacing="0" style="margin: 20px 0; border: 1px solid #ddd; border-radius: 6px;">
                    <tr>
                        <td style="background: #f8f8f8; font-weight: bold; width: 30%;">Product Name</td>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f8f8; font-weight: bold;">SKU</td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f8f8; font-weight: bold;">Current Stock</td>
                        <td style="color: #d32f2f; font-weight: bold;">{{ $product->stock_quantity }}</td>
                    </tr>
                </table>

                <p>Please consider restocking this item to avoid running out.</p>
                <p style="margin-top: 30px;">Thanks,<br><strong>[Mohamed  Hamed]</strong></p>
            </td>
        </tr>
        <tr>
            <td style="background: #f4f6f8; text-align: center; font-size: 12px; color: #777; padding: 15px; border-radius: 0 0 8px 8px;">
                © {{ date('Y') }} Mohamed Hamed. All rights reserved.
            </td>
        </tr>
    </table>

</body>
</html>
