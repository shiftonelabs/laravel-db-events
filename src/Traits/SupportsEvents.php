<?php
namespace ShiftOneLabs\LaravelDbEvents\Traits;

use Illuminate\Events\Dispatcher;

trait SupportsEvents
{

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * Get the event dispatcher used by the object.
     *
     * @return \Illuminate\Events\Dispatcher
     */
    public function getEventDispatcher()
    {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance on the object.
     *
     * In order to support both Laravel 4 and Laravel 5, the events
     * parameter is typehinted to the implementation and not the
     * interface, as the interface doesn't exist in Laravel 4.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function setEventDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Unset the event dispatcher instance on the object.
     *
     * @return void
     */
    public function unsetEventDispatcher()
    {
        $this->events = null;
    }

    /**
     * Check if the object is using events.
     *
     * @return boolean
     */
    public function usingEvents()
    {
        return isset($this->events);
    }

    /**
     * Fire the given event for the object.
     *
     * @param  object  $event
     * @param  boolean  $halt
     * @return mixed
     */
    protected function fireEvent($event, $halt = false)
    {
        if (!$this->usingEvents()) {
            return true;
        }

        $method = $halt ? 'until' : 'fire';

        return $this->events->$method(get_class($event), $event);
    }
}
