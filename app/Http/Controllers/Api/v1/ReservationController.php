<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\Reservation;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConfirmationMail;
use App\Mail\NewReservationMail;
use App\Mail\CanceledReservationMail;
use App\Mail\SuccessfulReservationMail;
use App\Models\Api\v1\ReservationCapacity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReservationController extends Controller
{

    public function test(){
            //TODO: Send a verification email
            $details = [
                'name' => 'name',
                'reservation_date' => 'test',
                'reservation_time' => '13:11',
                'number_of_people' => '3',
                'phone_number' => '00000000000',
                'token' => 'asdnasodasda12312312',
                'order_number' => '123asdasdasdasd',
                'cancel_key' => 'asdhasoud1d812do',
                'message' => 'Lasmdčasklbdviazguhijokdpdjnqbkvjahbjdhjnas dbaskc askbd asjl sd kajbdasklbhkdasklbd asdblasdjlhljhljhasnmd,sadnas,dnmadashdalsjdnmasdbkhauhiuguobhkn mnkjčhbvb njkhbh nkjhbh nkmjnbh nmk'
            ];

            Mail::to('test@email.com')->send(new SuccessfulReservationMail($details));
            return 'done';
    }
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json(['reservations' => $reservations], 200);
    }
    public function show(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'date' => ['required', 'date_format:Y-m-d']
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
        }
        $date = $request->date;
        $reservations = Reservation::where(['reservation_date' => $date, 'confirmed' => true])->get();
        return response()->json(['reservations' => $reservations], 200);
    }
    public function today()
    {
        $today = Carbon::now()->format('Y-m-d');
        $reservations = Reservation::where(['reservation_date' => $today, 'confirmed' => true])->orderBy('reservation_time', 'ASC')->get();
        return response()->json(['reservations' => $reservations], 200);
    }
    public function print_today()
    {
        //Get today's date
        $today = Carbon::now()->format('Y-m-d');
        $todayDate = Carbon::now()->format('d-m-Y');
        $timestamp = Carbon::now()->format('d-m-Y , H:i');
        $reservations = Reservation::where(['reservation_date' => $today, 'confirmed' => true])->orderBy('reservation_time', 'ASC')->get();
        $user = Auth::user()->name;
        $pdf = new Dompdf();
        $pdf->loadHtml(view('pdf.reservations', ['timestamp' => $timestamp, 'reservations' => $reservations, 'date' => $todayDate, 'user' => $user]));
        $pdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $pdf->render();

        // Output the generated PDF to Browser
        $pdf->stream();
        return response()->file(
            $pdf,
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
    public function print_date(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'date' => ['required', 'date_format:Y-m-d']
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
        }
        $date = $request->date;
        $timestamp = Carbon::now()->format('d-m-Y , H:i');
        $reservations = Reservation::where(['reservation_date' => $date, 'confirmed' => true])->orderBy('reservation_time', 'ASC')->get();
        $user = Auth::user()->name;
        $pdf = new Dompdf();
        $pdf->loadHtml(view('pdf.reservations', ['timestamp' => $timestamp, 'reservations' => $reservations, 'date' => $date, 'user' => $user]));
        $pdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $pdf->render();

        // Output the generated PDF to Browser
        $pdf->stream();
        return response()->file($pdf, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    public function cancel(Request $request)
    {
        if (Auth::user()->role == 'Admin' | Auth::user()->role == 'Manager') {
            //Do stuff
            $validator = Validator::make(
                $request->all(),
                [
                    'order_number' => ['required', 'string', 'max:255'],
                ]
            );
            $OrderNumber = $request->order_number;
            $ToBeDeleted = Reservation::where('order_number', $OrderNumber)->first();
            $ToBeDeleted->delete();
            return response()->json(['Status' => 'Successfully canceled the reservation'], 200);

            //TODO: a log file that gets updated every time someone of the staff cancels the reservation, name is logged and a timestamp with a reservation number

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            }
        } else {
            return response()->json(['Status' => 'Forbidden'], 403);
        }
    }

    //Public

    public function newReservation(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'reservation_date' => ['required', 'date_format:Y-m-d'],
                'reservation_time' => ['required', 'integer'],
                'number_of_people' => ['required', 'integer'],
                'phone_number' => ['required', 'string', 'max:255'],
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
        } else {
            $token = Str::random(32);
            $order_number = mt_rand(1000000000, 9999999999);

            $NewReservation = new Reservation();

            $today = Carbon::now()->format('Y-m-d');

            $NewReservation->name = $request->name;
            $NewReservation->email = $request->email;
            $NewReservation->reservation_date = $today;
            $NewReservation->order_number = $order_number;
            $NewReservation->reservation_time = $request->reservation_time;
            $NewReservation->number_of_people = $request->number_of_people;
            $NewReservation->phone_number = $request->phone_number;
            $NewReservation->token = $token;
            $NewReservation->confirmed = false;
            $NewReservation->save();

            //TODO: Send a verification email
            $details = [
                'name' => $request->name,
                'reservation_date' => $today,
                'reservation_time' => $request->reservation_time,
                'number_of_people' => $request->number_of_people,
                'phone_number' => $request->phone_number,
                'token' => $token,
            ];
            $email = $request->email;

            Mail::to($email)->send(new SendConfirmationMail($details));

            return response()->json(['Status' => 'Verification email sent', 'token' => $token], 201);
        }
    }       //Disable reservations via ReservationCapacity model
    //Max reservations -> ReservationCapacity Model
    // Upon veryfing, check with max capacity and update bool to true if the count of the group where reservation_date is not equal or larger than the max capacity model
    public function confirmReservation(Request $request)
    {
        $token = $request->token;
        $message = $request->message;
        $confirmedReservation = Reservation::where(['token' => $token, 'confirmed' => false])->first();
        if($confirmedReservation === null){
            return response()->json(["Status" => "Reservation has already been confirmed"], 410);
        }
        $max = ReservationCapacity::first()->daily_capacity;
        if (count(Reservation::where(['reservation_date' => $confirmedReservation->reservation_date, 'confirmed' => true])->get()) >= $max) {
            return response()->json(['Status' => 'Fully booked'], 410);
        }
        $cancelKey = Str::random(16);
        $hashedCancelKey = Hash::make($cancelKey);
        $confirmedReservation->cancel_key = $hashedCancelKey;
        $confirmedReservation->confirmed = true;
        $confirmedReservation->message = $message;
        $confirmedReservation->save();
        //TODO: Send a mail with cancel key

        $details = [
            'name' => $confirmedReservation->name,
            'order_number' => $confirmedReservation->order_number,
            'reservation_date' => $confirmedReservation->reservation_date,
            'reservation_time' => $confirmedReservation->reservation_time,
            'number_of_people' => $confirmedReservation->number_of_people,
            'phone_number' => $confirmedReservation->phone_number,
            'token' => $token,
            'cancel_key' => $cancelKey,
            'message' => $message
        ];
        $email = $confirmedReservation->email;
        Mail::to($email)->send(new SuccessfulReservationMail($details));
        Mail::to(env('RESERVATION_MAIL'))->send(new NewReservationMail($details));
        $email = $confirmedReservation->email;
        return response()->json(['Status' => 'Successfully booked'], 201);
    }
    public function resendConfirmationEmail(Request $request)
    {
        $token = $request->token;
        $reservation = Reservation::where(['token' => $token, 'confirmed' => false])->first();
        if($reservation === null){
            return response()->json(['Status' => 'You have already confirmed your reservation'], 410);
        }
        $details = [
            'name' => $reservation->name,
            'reservation_date' => $reservation,
            'reservation_time' => $reservation->reservation_time,
            'number_of_people' => $reservation->number_of_people,
            'phone_number' => $reservation->phone_number,
            'token' => $token,
            'order_number' => $reservation->order_number,
        ];
        $email = $reservation->email;
        Mail::to($email)->send(new SendConfirmationMail($details));

        return response()->json(['Status' => 'Verification email sent', 'token' => $token], 201);
    }
    public function delete(Request $request)
    {
        $token = $request->token;
        $reservation = Reservation::where(['token' => $token, 'confirmed' => true])->first();
        if($reservation === null){
            return response()->json(["Status" => "Reservation already canceled"], 410);
        }
        $cancel_key = $request->cancel_key;
        $hashed_key = $reservation->cancel_key;
        if (Hash::check($cancel_key, $hashed_key)) {
            $reservation->delete();
            $details = [
                'name' => $reservation->name,
                'reservation_date' => $reservation->reservation_date,
                'reservation_time' => $reservation->reservation_time,
                'number_of_people' => $reservation->number_of_people,
                'phone_number' => $reservation->phone_number,
                'order_number' => $reservation->order_number,
            ];
            Mail::to(env('RESERVATION_MAIL'))->send(new CanceledReservationMail($details));
            return response()->json(['Status' => 'Successfully canceled'], 201);
        } else {
            return response()->json(['Status' => 'Invalid key'], 400);
        }
    }
    //If the client wants, make a function that uses vonage/nexmo or something to send a reminder 1-2 hours before the appointment
}
