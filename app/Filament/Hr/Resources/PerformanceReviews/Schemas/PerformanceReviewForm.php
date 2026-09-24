<?php

namespace App\Filament\Hr\Resources\PerformanceReviews\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* =======================
               REVIEW INFORMATION
            ======================= */
            Section::make('Review Informations')
                ->columns(2)
                ->schema([

                    Select::make('user_id')
                        ->label('User')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('reviewer_id')
                        ->label('Reviewer')
                        ->relationship('reviewer', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    DatePicker::make('review_start')
                        ->label('Review Start Date')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                            self::updateReviewPeriod($set, $get)
                        ),

                    DatePicker::make('review_end')
                        ->label('Review End Date')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                            self::updateReviewPeriod($set, $get)
                        ),

                    TextInput::make('review_period')
                        ->label('Review Period')
                        ->disabled()       // cannot edit manually
                        ->dehydrated()     // still saved to DB
                        ->required()
                        ->columnSpanFull(),
                ]),

            /* =======================
               PERFORMANCE METRICS
            ======================= */
            Section::make('Performance Metrics (1–10)')
                ->columns(2)
                ->schema([

                    TextInput::make('quality_of_work')
                        ->required()
                        ->minValue(1)
                        ->maxValue(10)
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                            self::calculateOverallRating($set, $get)
                        ),

                    TextInput::make('productivity')
                        ->required()
                        ->minValue(1)
                        ->maxValue(10)
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                            self::calculateOverallRating($set, $get)
                        ),

                    TextInput::make('communication')
                        ->required()
                        ->minValue(1)
                        ->maxValue(10)
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                            self::calculateOverallRating($set, $get)
                        ),

                    TextInput::make('teamwork')
                        ->required()
                        ->minValue(1)
                        ->maxValue(10)
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                            self::calculateOverallRating($set, $get)
                        ),

                    TextInput::make('leadership')
                        ->required()
                        ->minValue(1)
                        ->maxValue(10)
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                            self::calculateOverallRating($set, $get)
                        ),

                    TextInput::make('overall_rating')
                        ->label('Overall Rating')
                        ->suffix(' / 10')
                        ->disabled()
                        ->dehydrated()
                        ->numeric(),
                ]),

            /* =======================
               FEEDBACK & GOALS
            ======================= */
            Section::make('Feedback and Goals')
                ->columns(2)
                ->columnSpanFull()
                ->schema([

                    Textarea::make('strengths')
                        ->columnSpanFull(),

                    Textarea::make('areas_for_improvement')
                        ->columnSpanFull(),

                    Textarea::make('goals')
                        ->columnSpanFull(),

                    Textarea::make('comments')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    /* =======================
       AUTO REVIEW PERIOD
    ======================= */
    protected static function updateReviewPeriod(Set $set, Get $get): void
    {
        $start = $get('review_start');
        $end   = $get('review_end');

        if ($start && $end) {
            $set(
                'review_period',
                date('d/m/Y', strtotime($start)) . ' to ' . date('d/m/Y', strtotime($end))
            );
        }
    }

    /* =======================
       AUTO OVERALL RATING
    ======================= */
    protected static function calculateOverallRating(Set $set, Get $get)
    {
        $quality      = (int) $get('quality_of_work');
        $productivity = (int) $get('productivity');
        $communication= (int) $get('communication');
        $teamwork     = (int) $get('teamwork');
        $leadership   = (int) $get('leadership');

        if ($quality && $productivity && $communication && $teamwork && $leadership) {
            $overall = round(
                ($quality + $productivity + $communication + $teamwork + $leadership) / 5,
                2
            );

            $set('overall_rating', $overall);
        }
    }
}
