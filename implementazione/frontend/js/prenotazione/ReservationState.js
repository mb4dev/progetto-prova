class ReservationState {
	constructor() {
        this.selectedSport = null;
		this.selectedDate = null;
		this.selectedSlots = new Set();
		this.occupiedSlots = [];
		this.week = this.#getWeekDays();
	}

    startNewReservation(sport){
        this.selectedSport = sport;
        this.selectedDate = null;
		this.selectedSlots = new Set();
		this.occupiedSlots = [];
		this.week = this.#getWeekDays();
    }

	#getWeekDays(fromDate = new Date(), daysToShow = 7) {
        const options = { weekday: "long" };
        const days = [];
        for (let i = 0; i < daysToShow; i++) {
            const date = new Date(fromDate);
            date.setDate(date.getDate() + i);
            days.push({
                giorno: date.toLocaleDateString("it-IT", options),
                numero: String(date.getDate()).padStart(2, '0'),
                mese: String(date.getMonth() + 1).padStart(2, '0'),
                fullDate: date.toISOString().split('T')[0],
            });
        }
        return days;
    }

	changeWeek(days, callback) {
        const firstDay = new Date(this.week[0].fullDate);
        firstDay.setDate(firstDay.getDate() + days);

        if (days < 0) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (firstDay < today) return;
        }

        this.week = this.#getWeekDays(firstDay);
		callback()
    }


	clear(){
		this.selectedSlots.clear()
	}
}

const reservationState = new ReservationState();
export default reservationState;

